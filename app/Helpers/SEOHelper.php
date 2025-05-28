<?php

namespace App\Helpers;

class SEOHelper
{
    /**
     * Generate meta tags
     *
     * @param array $data
     * @return string
     */
    public static function generateMetaTags(array $data): string
    {
        $metaTags = [];

        // Basic meta tags
        if (isset($data['title'])) {
            $metaTags[] = '<title>' . htmlspecialchars($data['title']) . '</title>';
            $metaTags[] = '<meta name="title" content="' . htmlspecialchars($data['title']) . '">';
        }

        if (isset($data['description'])) {
            $metaTags[] = '<meta name="description" content="' . htmlspecialchars($data['description']) . '">';
        }

        if (isset($data['keywords'])) {
            $keywords = is_array($data['keywords']) ? implode(', ', $data['keywords']) : $data['keywords'];
            $metaTags[] = '<meta name="keywords" content="' . htmlspecialchars($keywords) . '">';
        }

        // Open Graph meta tags
        if (isset($data['og'])) {
            foreach ($data['og'] as $property => $content) {
                $metaTags[] = '<meta property="og:' . $property . '" content="' . htmlspecialchars($content) . '">';
            }
        }

        // Twitter Card meta tags
        if (isset($data['twitter'])) {
            foreach ($data['twitter'] as $name => $content) {
                $metaTags[] = '<meta name="twitter:' . $name . '" content="' . htmlspecialchars($content) . '">';
            }
        }

        // Additional meta tags
        if (isset($data['meta'])) {
            foreach ($data['meta'] as $name => $content) {
                $metaTags[] = '<meta name="' . $name . '" content="' . htmlspecialchars($content) . '">';
            }
        }

        return implode("\n", $metaTags);
    }

    /**
     * Generate canonical URL
     *
     * @param string $url
     * @return string
     */
    public static function generateCanonical(string $url): string
    {
        return '<link rel="canonical" href="' . htmlspecialchars($url) . '">';
    }

    /**
     * Generate robots meta tag
     *
     * @param array $rules
     * @return string
     */
    public static function generateRobots(array $rules = []): string
    {
        $defaultRules = ['index', 'follow'];
        $rules = !empty($rules) ? $rules : $defaultRules;
        
        return '<meta name="robots" content="' . implode(', ', $rules) . '">';
    }

    /**
     * Generate JSON-LD schema
     *
     * @param array $data
     * @return string
     */
    public static function generateJsonLd(array $data): string
    {
        return '<script type="application/ld+json">' . json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>';
    }

    /**
     * Generate alternate language links
     *
     * @param array $languages
     * @return string
     */
    public static function generateAlternateLanguages(array $languages): string
    {
        $links = [];
        foreach ($languages as $lang => $url) {
            $links[] = '<link rel="alternate" hreflang="' . htmlspecialchars($lang) . '" href="' . htmlspecialchars($url) . '">';
        }
        
        return implode("\n", $links);
    }

    /**
     * Generate sitemap XML
     *
     * @param array $urls
     * @return string
     */
    public static function generateSitemap(array $urls): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($urls as $url) {
            $xml .= "\t<url>\n";
            $xml .= "\t\t<loc>" . htmlspecialchars($url['loc']) . "</loc>\n";
            
            if (isset($url['lastmod'])) {
                $xml .= "\t\t<lastmod>" . $url['lastmod'] . "</lastmod>\n";
            }
            
            if (isset($url['changefreq'])) {
                $xml .= "\t\t<changefreq>" . $url['changefreq'] . "</changefreq>\n";
            }
            
            if (isset($url['priority'])) {
                $xml .= "\t\t<priority>" . number_format($url['priority'], 1) . "</priority>\n";
            }
            
            $xml .= "\t</url>\n";
        }

        $xml .= '</urlset>';
        
        return $xml;
    }

    /**
     * Generate breadcrumbs JSON-LD
     *
     * @param array $items
     * @return string
     */
    public static function generateBreadcrumbs(array $items): string
    {
        $breadcrumbs = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => []
        ];

        foreach ($items as $position => $item) {
            $breadcrumbs['itemListElement'][] = [
                '@type' => 'ListItem',
                'position' => $position + 1,
                'name' => $item['name'],
                'item' => $item['url']
            ];
        }

        return self::generateJsonLd($breadcrumbs);
    }

    /**
     * Generate social media meta tags
     *
     * @param array $data
     * @return string
     */
    public static function generateSocialTags(array $data): string
    {
        $tags = [];

        // Facebook
        if (isset($data['facebook'])) {
            $tags[] = '<meta property="fb:app_id" content="' . htmlspecialchars($data['facebook']['app_id']) . '">';
            if (isset($data['facebook']['admins'])) {
                $tags[] = '<meta property="fb:admins" content="' . htmlspecialchars($data['facebook']['admins']) . '">';
            }
        }

        // Twitter
        if (isset($data['twitter'])) {
            $tags[] = '<meta name="twitter:card" content="' . htmlspecialchars($data['twitter']['card']) . '">';
            if (isset($data['twitter']['site'])) {
                $tags[] = '<meta name="twitter:site" content="' . htmlspecialchars($data['twitter']['site']) . '">';
            }
        }

        return implode("\n", $tags);
    }

    /**
     * Clean and format URL for SEO
     *
     * @param string $url
     * @return string
     */
    public static function formatUrl(string $url): string
    {
        // Remove multiple slashes
        $url = preg_replace('/([^:])(\/{2,})/', '$1/', $url);
        
        // Remove trailing slash
        $url = rtrim($url, '/');
        
        // Convert to lowercase
        $url = strtolower($url);
        
        // Remove query parameters
        $url = preg_replace('/\?.*/', '', $url);
        
        return $url;
    }

    /**
     * Generate meta description from text
     *
     * @param string $text
     * @param int $maxLength
     * @return string
     */
    public static function generateMetaDescription(string $text, int $maxLength = 160): string
    {
        // Strip HTML tags
        $text = strip_tags($text);
        
        // Convert special characters
        $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
        
        // Remove extra whitespace
        $text = preg_replace('/\s+/', ' ', $text);
        
        // Trim to max length
        $text = substr($text, 0, $maxLength);
        
        // Trim to last complete word
        $text = preg_replace('/\s+?(\S+)?$/', '', $text);
        
        return $text;
    }
} 