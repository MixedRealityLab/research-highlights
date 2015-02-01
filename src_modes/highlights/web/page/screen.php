<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

use PhpOffice\PhpPowerpoint\IOFactory;
use PhpOffice\PhpPowerpoint\PhpPowerpoint;
use PhpOffice\PhpPowerpoint\DocumentLayout;
use PhpOffice\PhpPowerpoint\Style\Alignment;
use PhpOffice\PhpPowerpoint\Style\Color;

// Serve a PowerPoint/ODP of all the tweets submitted

$file = SITE_NAME . ' ' . SITE_YEAR .'.pptx';
$filePath = DIR_CAC . '/' . $file;

\clearstatcache (true, $filePath);
if (!\is_file ($filePath) || (\is_file ($filePath) && \filemtime ($filePath) + CACHE_SCREEN > \date ('U'))) {

	$cSubmission = I::RH_Submission ();
	$mInput = I::RH_Model_Input ();
	$cUser = I::RH_User ();

	$oPowerpoint = new PhpPowerpoint ();
	$oPowerpoint->getProperties ()->setCreator (VERSION)
				->setLastModifiedBy (VERSION)
				->setTitle (SITE_NAME . ' ' . SITE_YEAR)
				->setSubject (SITE_NAME)
				->setLooped (true);

	// Set to 16x9
	$oLayout = new DocumentLayout();
	$oLayout->setDocumentLayout(\PhpOffice\PhpPowerpoint\DocumentLayout::LAYOUT_SCREEN_16X9);
	$oPowerpoint->setLayout ($oLayout);

	$oPowerpoint->removeSlideByIndex (0);

	// Which tweets should be displayed?
	if (isSet ($mInput->user)) {
		$mUsers = array ($cUser->get ($mInput->user));
	} else if (isSet ($mInput->cohort)) {
		$cohort = $mInput->cohort;
		$mUsers = $cUser->getAll (null, function ($user) use ($cohort) {
			return $user->countSubmission && $user->cohort === $cohort;
		});
	} else {
		$mUsers = $cUser->getAll (null, function ($user) {
			return $user->countSubmission;
		});
	}

	$usernames = (\array_keys ($mUsers->getArrayCopy ()));
	\shuffle ($usernames);

	// Create slides
	foreach ($usernames as $username) {
		$mUser = $mUsers->$username;
		try {
			$mSubmission = $cSubmission->get ($mUser, false);

			if (!isSet ($mSubmission->tweet)) {
				continue;
			}

			$slide = $oPowerpoint->createSlide();

			$slide->setAdvancement (20000);

			$shape = $slide->createDrawingShape();
			$shape->setName ('Horizon CDT header')
						->setDescription ('Horizon Centre for Doctoral Training')
						->setPath (DIR_WIM . '/screen-header.png')
						->setWidth (961)
						->setOffsetX (0)
						->setOffsetY (0);

			$shape = $slide->createRichTextShape()
				->setHeight(200)
				->setWidth(881)
				->setOffsetX(40)
				->setOffsetY(140)
				->setInsetTop(0)
				->setInsetBottom(0);
			$shape->getActiveParagraph()->getAlignment()
					->setHorizontal (Alignment::HORIZONTAL_LEFT)
					->setVertical (Alignment::VERTICAL_BOTTOM);
			$tweet = $shape->createTextRun ($mSubmission->tweet);
			$tweet->getFont()->setBold (false)
					->setName('Helvetica Neue')
					->setSize (30)
					->setColor (new Color ('FF000000'));

			$line = $slide->createLineShape(40, 360, 915, 360);
			$line->getBorder()->setColor (new Color ('FF000000'));

			$name = $mUser->firstName . ' ' . $mUser->surname . ' (' . $mUser->cohort . ' cohort)';

			$shape = $slide->createRichTextShape()
				->setHeight(50)
				->setWidth(881)
				->setOffsetX(40)
				->setOffsetY(380)
				->setInsetTop(0)
				->setInsetBottom(0);
			$shape->getActiveParagraph()->getAlignment()
					->setHorizontal (Alignment::HORIZONTAL_LEFT)
					->setVertical (Alignment::VERTICAL_TOP);
			$author = $shape->createTextRun ($name);
			$author->getFont()->setBold (false)
					->setName('Helvetica Neue')
					->setSize (14)
					->setColor (new Color ('FF333333'));

			$link = 'find out more at ' . URI_HOME . '/';
			$shape = $slide->createRichTextShape()
				->setHeight(50)
				->setWidth(881)
				->setOffsetX(40)
				->setOffsetY(380)
				->setInsetTop(0)
				->setInsetBottom(0);
			$shape->getActiveParagraph()->getAlignment()
					->setHorizontal (Alignment::HORIZONTAL_RIGHT)
					->setVertical (Alignment::VERTICAL_TOP);
			$author = $shape->createTextRun ($link);
			$author->getFont()->setBold (false)
					->setName('Helvetica Neue')
					->setSize (14)
					->setColor (new Color ('FF0C2577'));

			$shape = $slide->createDrawingShape();
			$shape->setName ('Horizon CDT footer')
						->setPath (DIR_WIM . '/screen-footer.png')
						->setWidth (921)
						->setOffsetX (20)
						->setOffsetY (470);
		} catch (\RH\Error\NoSubmission $e) {
		}
	}

	// Save, serve then delete
	$file = SITE_NAME . ' ' . SITE_YEAR .'.pptx';

	$oWriterPPTx = IOFactory::createWriter ($oPowerpoint, 'PowerPoint2007');
	$oWriterPPTx->save (DIR_CAC . '/' . $file);
}

\header ('Content-Disposition: attachment; filename="' . $file . '"');
\header ('Content-type: application/vnd.openxmlformats-officedocument.presentationml.presentation');

\readfile (DIR_CAC . '/' . $file);
