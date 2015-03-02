<?php
/**
 * JEvents Component for Joomla 1.5.x
 *
 * @version     $Id: cpanel.php 3119 2011-12-20 14:34:33Z geraintedwards $
 * @package     JEvents
 * @copyright   Copyright (C)  2008-2015 GWE Systems Ltd
 * @license     GNU/GPLv2, see http://www.gnu.org/licenses/gpl-2.0.html
 * @link        http://www.jevents.net
 */
defined('_JEXEC') or die('Restricted access');

// Check if we are saving here.
if (JRequest::getVar('save')) {
    customCssSave();
}
?>
<div id="jevents">
    <?php
    if (isset($this->warning)) {
        ?>
        <dl id="system-message">
            <dt class="notice">Message</dt>
            <dd class="notice fade">
                <ul>
                    <li><?php echo $this->warning; ?></li>
                </ul>
            </dd>
        </dl>
    <?php
    }

    $file = 'jevcustom.css';
    $srcfile = 'jevcustom.css.new';
    $filepath = JPATH_ROOT . '/components/com_jevents/assets/css/' . $file;
    $srcfilepath = JPATH_ROOT . '/components/com_jevents/assets/css/' . $srcfile;
    if (!JFile::exists($filepath)) {
        $filepath = $srcfilepath;
    }
    $content = '';
    $html = '';

    ob_start();

    $content = JFile::read($filepath);
    $btnclass = JevJoomlaVersion::isCompatible("3.0") ? "btn btn-success" : "";
    ?>

    <form action="index.php?option=com_jevents" method="post"
          name="admin" id="adminForm">
        <?php echo JHtml::_( 'form.token' ); ?>
        <?php if (!empty($this->sidebar)) : ?>
        <div id="j-sidebar-container" class="span2">
            <?php echo $this->sidebar; ?>
        </div>
        <div id="j-main-container" class="span10">
            <?php else : ?>
            <div id="j-main-container">
                <?php endif; ?>
                <textarea style="width:60%;height:550px;" name="content"><?php echo $content; ?></textarea>
                <input type="hidden" name="controller" value="component" />
                <input type="hidden" name="option" value="<?php echo JEV_COM_COMPONENT; ?>" />
                <input type="hidden" name="task" value="" />
                <input type="hidden" name="save" value="custom_css_save" />
            </div>
        </div>
    </form>
    <?php
    $html = ob_get_contents();
    @ob_end_clean();

    echo $html;

    function customCssSave()
    {
        //Check for request forgeries
        JSession::checkToken() or die( 'Invalid Token' );
        $mainframe = JFactory::getApplication();

        $file = 'jevcustom.css';
        $filepath = JPATH_ROOT . '/components/com_jevents/assets/css/' . $file;
        $jinput = JFactory::getApplication()->input;
        $content = $jinput->get('content', '', 'POST', '', 'RAW');

        $msg = '';
        $msgType = '';

        $status = JFile::write($filepath, $content);
        if (!empty($status)) {
            $msg = JText::_('JEV_CUSTOM_CSS_SUCCESS');
            $msgType = 'Info';
        } else {
            $msg = JText::_('JEV_CUSTOM_CSS_ERROR');
            $msgType = 'Error';
        }

        $mainframe->enqueueMessage($msg, $msgType);
        $mainframe->redirect('index.php?option=com_jevents&task=cpanel.custom_css');

    }

    ?>

</div>
