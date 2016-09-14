<?php

/**
 * Research Highlights engine
 *
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

// Generate an XML sitemap

try {
    $submissions = I::RH_User()->getAll(null, function ($mUser) {
        return $mUser->latestVersion && $mUser->countSubmission;
    });
} catch (\RH\Error $e) {
    print $e->toJson();
    exit;
}

\header('Content-type: application/xml');

print '<?xml version="1.0" encoding="UTF-8"?>';
?><urlset
      xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
    <url>
        <loc><?php print \htmlspecialchars(URI_ROOT); ?></loc>
        <changefreq>yearly</changefreq>
        <priority>1</priority>
    </url>
<?php foreach ($submissions as $submission) : ?>
    <url>
        <loc><?php print \htmlspecialchars(URI_ROOT .'/read/' . $submission->username); ?></loc>
        <changefreq>yearly</changefreq>
        <lastmod><?php $dt = new \DateTime();
        $dt->setTimestamp($submission->latestVersion);
        print $dt->format(\DateTime::W3C); ?></lastmod>
    </url>
<?php endforeach; ?>
</urlset>