<?php
/**
 * WP File Download
 *
 * @package WP File Download
 * @author  Joomunited
 * @version 1.0
 */

use Joomunited\WPFramework\v1_0_5\View;
use Joomunited\WPFramework\v1_0_5\Form;
use Joomunited\WPFramework\v1_0_5\Application;
use Joomunited\WPFramework\v1_0_5\Utilities;

defined('ABSPATH') || die();

/**
 * Class WpfdViewConfig
 */
class WpfdViewConfig extends View
{
    /**
     * Render view config
     *
     * @param null|string $tpl Template name
     *
     * @return void
     */
    public function render($tpl = null)
    {
        Application::getInstance('Wpfd');
        $modelConf   = $this->getModel('config');
        $this->theme = $modelConf->getThemeConfig();
        if ($this->theme === '') {
            $this->theme = 'default';
        }
        $this->config          = $modelConf->getConfig();
        $this->file_config     = $modelConf->getFileConfig();
        $this->search_config   = $modelConf->getSearchConfig();
        $this->upload_config   = $modelConf->getUploadConfig();
        $this->file_cat_config = $modelConf->getFileInCatConfig();
        $this->themes          = $modelConf->getThemes();
        $form                  = new Form();
        foreach ($this->themes as $themName) {
            if (WpfdBase::checkExistTheme($themName)) {
                $formfile = Application::getInstance('Wpfd')->getPath() . DIRECTORY_SEPARATOR . 'site';
                $formfile .= DIRECTORY_SEPARATOR . 'themes' . DIRECTORY_SEPARATOR . 'wpfd-' . $themName;
                $formfile .= DIRECTORY_SEPARATOR . 'form.xml';
            } else {
                $formfile = wpfd_locate_theme($themName, 'form.xml');
            }
            $themeConfig = $modelConf->getThemeParams($themName);
            if ($form->load($formfile, $themeConfig)) {
                $this->themeforms[$themName] = $form->render();
            } else {
                $this->themeforms[$themName] = '';
            }
        }
        $form = new Form();
        if ($form->load('config', $this->config)) {
            $this->configform = $form->render();
            $this->configform = str_replace('text2', 'text', $this->configform);
        }
        $file_form = new Form();
        if ($file_form->load('file_config', $this->file_config)) {
            $this->file_configform = $file_form->render();
        }
        $search_form = new Form();
        if ($search_form->load('search', $this->search_config)) {
            $this->searchform = $search_form->render();
        }
        $clone_form = new Form();
        if ($clone_form->load('clone', array())) {
            $this->clone_form = $clone_form->render();
        }
        $upload_form = new Form();
        if ($upload_form->load('upload', $this->upload_config)) {
            $this->upload_form = $upload_form->render();
        }
        $file_cat_form = new Form();
        if ($file_cat_form->load('file_cat_sortcode', $this->file_cat_config)) {
            $this->file_catform = $file_cat_form->render();
        }

        if (defined('WPFD_ADMIN_UI') && WPFD_ADMIN_UI === true) {
            // juTranslate tab content
            $juTranslateContent = '';

            ob_start();
            \Joomunited\WPFileDownload\Jutranslation\Jutranslation::getInput();
            $juTranslateContent = ob_get_contents();
            ob_end_clean();

            $this->translate_form = $juTranslateContent;

            // Notification
            Application::getInstance('Wpfd');
            $modelNotify                = $this->getModel('notification');
            $this->notifications_config = $modelNotify->getNotificationsConfig();
            $this->mail_option_config   = $modelNotify->getMailOptionConfig();
            $notifications_form         = new Form();
            if ($notifications_form->load('notifications', $this->notifications_config)) {
                $this->notifications_form['email_notication_editor'] = $notifications_form->render();
            }

            Application::getInstance('Wpfd');
            $mailoption_form = new Form();
            if ($mailoption_form->load('mail_option', $this->mail_option_config)) {
                $this->notifications_form['mail_option'] = $mailoption_form->render();
            }

            // User roles
            $this->rolesform = wpfd_admin_ui_user_roles_content();

            add_action('wpfd_admin_ui_configuration_content', array($this, 'buildConfigContents'), 10, 1);

            $tpl = 'ui-default';
        }
        parent::render($tpl);
    }

    /**
     * Build config content
     *
     * @return void
     */
    public function buildConfigContents()
    {
        $html = '';
        $menuItems = wpfd_admin_ui_configuration_menu_get_items();

        foreach ($menuItems as $key => $item) {
            if (isset($item[1]) && trim($item[1]) !== '') {
                $multiHtml = '';
                $forms     = explode(',', $item[1]);
                foreach ($forms as $form) {
                    if (is_array($this->{$form})) {
                        $multiHtml .= wpfd_admin_ui_configuration_build_tabs($this->{$form});
                    } else {
                        $multiHtml .= $this->{$form};
                    }
                }
                $html .= wpfd_admin_ui_configuration_build_content($key, $multiHtml);
            }
        }
        // phpcs:ignore -- escaped
        echo $html;
    }
}
