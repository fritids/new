<?php

/*
 * Transposh v0.7.6
 * http://transposh.org/
 *
 * Copyright 2011, Team Transposh
 * Licensed under the GPL Version 2 or higher.
 * http://transposh.org/license
 *
 * Date: Tue, 02 Aug 2011 03:11:42 +0300
 */

/*
 * This file handles functions relevant to specific third party plugins
 */

class transposh_3rdparty {

    /** @var transposh_plugin Container class */
    private $transposh;

    /**
     * Construct our class
     * @param transposh_plugin $transposh
     */
    function transposh_3rdparty(&$transposh) {
        $this->transposh = &$transposh;

        // supercache invalidation of pages - first lets find if supercache is here
        if (function_exists('wp_super_cache_init')) {
            add_action('transposh_translation_posted', array(&$this, 'super_cache_invalidate'));
        }

        // buddypress compatability
        add_filter('bp_uri', array(&$this, 'bp_uri_filter'));
        add_filter('bp_get_activity_content_body', array(&$this, 'bp_get_activity_content_body'), 10, 2);
        add_action('bp_activity_after_save', array(&$this, 'bp_activity_after_save'));
        add_action('transposh_human_translation', array(&$this, 'transposh_buddypress_stream'), 10, 3);
        //bp_activity_permalink_redirect_url (can fit here if generic setting fails)
        // google xml sitemaps - with patch
        add_action('sm_addurl', array(&$this, 'add_sm_transposh_urls'));

        // google analyticator
        if ($this->transposh->options->get_transposh_collect_stats()) {
            add_action('google_analyticator_extra_js_after', array(&$this, 'add_analyticator_tracking'));
        }
    }

    function add_analyticator_tracking() {
        echo "	_gaq.push(['_setAccount', 'UA-4663695-5']);\n";
        echo "	_gaq.push(['_setDomainName', 'none']);\n";
        echo "	_gaq.push(['_setAllowLinker', true]);\n";
        echo "	_gaq.push(['_trackPageview']);\n";
    }

    function super_cache_invalidate() {
        //Now, we are actually using the referrer and not the request, with some precautions
        $GLOBALS['wp_cache_request_uri'] = substr($_SERVER['HTTP_REFERER'], stripos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']) + strlen($_SERVER[''] . $_SERVER['HTTP_HOST']));
        $GLOBALS['wp_cache_request_uri'] = preg_replace('/[ <>\'\"\r\n\t\(\)]/', '', str_replace('/index.php', '/', str_replace('..', '', preg_replace("/(\?.*)?$/", '', $GLOBALS['wp_cache_request_uri']))));
        // get some supercache variables
        extract(wp_super_cache_init());
        
        // this is hackery for logged in users, a cookie is added to the request somehow and gzip is not correctly set, so we forcefully fix this
        if (!$cache_file) {
            $GLOBALS['wp_cache_gzip_encoding'] = gzip_accepted();
            unset($_COOKIE[key($_COOKIE)]);
            extract(wp_super_cache_init());
            
        }

        $dir = get_current_url_supercache_dir();
        // delete possible files that we can figure out, not deleting files for other cookies for example, but will do the trick in most cases
        $cache_fname = "{$dir}index.html";
        
        @unlink($cache_fname);
        $cache_fname = "{$dir}index.html.gz";
        
        @unlink($cache_fname);
        
        @unlink($cache_file);
        
        @unlink($meta_pathname);

        // go at edit pages too
        $GLOBALS['wp_cache_request_uri'] .="?edit=1";
        extract(wp_super_cache_init());
        
        
        @unlink($cache_file);
        
        @unlink($meta_pathname);
    }

    /**
     * This filter method helps buddypress understand the transposh generated URLs
     * @param string $uri The url that was originally received
     * @return string The url that buddypress should see
     */
    function bp_uri_filter($uri) {
        $lang = transposh_utils::get_language_from_url($uri, $this->transposh->home_url);
        //TODO - check using get_clean_url
        $uri = transposh_utils::cleanup_url($uri, $this->transposh->home_url);
        if ($this->transposh->options->get_enable_url_translate()) {
            $uri = transposh_utils::get_original_url($uri, '', $lang, array($this->transposh->database, 'fetch_original'));
        }
        return $uri;
    }

    /**
     * After saving action, makes sure activity has proper language
     * @param BP_Activity_Activity $params
     */
    function bp_activity_after_save($params) {
        // we don't need to modify our own activity stream
        if ($params->type == 'new_translation') return;
        if (transposh_utils::get_language_from_url($_SERVER['HTTP_REFERER'], $this->transposh->home_url))
                bp_activity_update_meta($params->id, 'tp_language', transposh_utils::get_language_from_url($_SERVER['HTTP_REFERER'], $this->transposh->home_url));
    }

    /**
     * Change the display of activity content using the transposh meta
     * @param string $content
     * @param BP_Activity_Activity $activity
     * @return string modified content
     */
    function bp_get_activity_content_body($content, $activity) {
        $activity_lang = bp_activity_get_meta($activity->id, 'tp_language');
        if ($activity_lang) {
            $content = "<span lang =\"$activity_lang\">" . $content . "</span>";
        }
        return $content;
    }

    /**
     * Add an item to the activity string upon translation
     * @global object $bp the global buddypress
     * @param string $translation
     * @param string $original
     * @param string $lang
     */
    function transposh_buddypress_stream($translation, $original, $lang) {
        global $bp;

        // we must have buddypress...
        if (!function_exists('bp_activity_add')) return false;

        // we only log translation for logged on users
        if (!$bp->loggedin_user->id) return;

        /* Because blog, comment, and blog post code execution happens before anything else
          we may need to manually instantiate the activity component globals */
        if (!$bp->activity && function_exists('bp_activity_setup_globals'))
                bp_activity_setup_globals();

        // just got this from buddypress, changed action and content
        $values = array(
            'user_id' => $bp->loggedin_user->id,
            'action' => sprintf(__('%s translated a phrase to %s with transposh:', 'buddypress'), bp_core_get_userlink($bp->loggedin_user->id), substr(transposh_consts::$languages[$lang], 0, strpos(transposh_consts::$languages[$lang], ','))),
            'content' => "Original: <span class=\"no_translate\">$original</span>\nTranslation: <span class=\"no_translate\">$translation</span>",
            'primary_link' => '',
            'component' => $bp->blogs->id,
            'type' => 'new_translation',
            'item_id' => false,
            'secondary_item_id' => false,
            'recorded_time' => gmdate("Y-m-d H:i:s"),
            'hide_sitewide' => false
        );

        return bp_activity_add($values);
    }

    /**
     * This function integrates with google sitemap generator, and adds for each viewable language, the rest of the languages url
     * Also - priority is reduced by 0.2
     * And this requires the following line at the sitemap-core.php, add-url function (line 1509 at version 3.2.4)
     * do_action('sm_addurl', $page);
     * @param GoogleSitemapGeneratorPage $sm_page Object containing the page information
     */
    function add_sm_transposh_urls($sm_page) {
        
        $sm_page = clone $sm_page;
        // we need the generator object (we know it must exist...)
        $generatorObject = &GoogleSitemapGenerator::GetInstance();
        // we reduce the priorty by 0.2, but not below zero
        $sm_page->SetProprity(max($sm_page->GetPriority() - 0.2, 0));

        $viewable_langs = explode(',', $this->transposh->options->get_viewable_langs());
        $orig_url = $sm_page->GetUrl();
        foreach ($viewable_langs as $lang) {
            if (!$this->transposh->options->is_default_language($lang)) {
                $newloc = $orig_url;
                if ($this->transposh->options->get_enable_url_translate()) {
                    $newloc = transposh_utils::translate_url($newloc, $this->transposh->home_url, $lang, array(&$this->transposh->database, 'fetch_translation'));
                }
                $newloc = transposh_utils::rewrite_url_lang_param($newloc, $this->transposh->home_url, $this->transposh->enable_permalinks_rewrite, $lang, false);
                $sm_page->SetUrl($newloc);
                $generatorObject->AddElement($sm_page);
            }
        }
    }

}

?>
