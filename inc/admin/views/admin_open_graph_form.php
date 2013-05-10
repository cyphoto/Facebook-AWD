<?php

use AHWEBDEV\AWD_facebook_form;

/**
 * View Admin opegraph form template
 * @author AHWEBDEV (Alexandre Hermann) [hermann.alexandre@ahwebev.fr]
 */
?>
<h1><?php _e('1. Define Object', self::PTD); ?></h1>

<?php
$object = array(
    'id' => '',
    'title' => '%TITLE%',
    'description' => '%DESCRIPTION%',
    'custom_type' => isset($this->options['app_infos']['namespace']) ? $this->options['app_infos']['namespace'] . ':' : '' . 'Your_custom_type',
    'type' => 'article',
    'url' => '%URL%',
    'site_name' => '%BLOG_TITLE%',
    'auto_load_videos_attachment' => 0,
    'auto_load_images_attachment' => 0,
    'auto_load_audios_attachment' => 0
);
$form = new AWD_facebook_form('form_create_opengraph_object', 'POST', null, self::OPTION_PREFIX);
if ($objectId instanceof AWD_facebook_form) {
    $form = $objectId;
} else if (is_array($objectId)) {
    $object = $objectId;
} else if ($objectId != '') {
    $object = wp_parse_args($this->options['opengraph_objects'][$objectId], $object);
}

if ($copy == 'true')
    $object['id'] = '';
if (!isset($object['object_title']))
    $object['object_title'] = '';

$ogp = $this->opengraphArrayToObject($object);

if (!is_array($objectId))
    echo $form->start();
?>
<div class="alert alert-info">
    <p><?php _e('Template Values: you can use those values in each field of the form, select the input where you want to place a variable then click on the button of your choice.', self::PTD); ?></p>
    <div class="btn-group opengraph_placeholder">
        <button class="btn btn-mini">%BLOG_TITLE%</button>
        <button class="btn btn-mini">%BLOG_DESCRIPTION%</button>
        <button class="btn btn-mini">%BLOG_URL%</button>
        <button class="btn btn-mini">%TITLE%</button>
        <button class="btn btn-mini">%DESCRIPTION%</button>
        <button class="btn btn-mini">%IMAGE%</button>
        <button class="btn btn-mini">%URL%</button>
    </div>
</div>
<div class="row">
    <?php
    //id of object
    echo $form->addInputHidden('awd_ogp[id]', $object['id']);
    //title of object
    echo $form->addInputText(__('Title of object (only for reference)', self::PTD), 'awd_ogp[object_title]', $object['object_title'], 'span4', array('class' => 'span4'));
    //Locale
    $locales = $ogp->supported_locales();
    $_locales = array();
    foreach ($locales as $locale => $label) {
        $_locales[] = array('value' => $locale, 'label' => $label);
    }
    echo $form->addSelect(__('Locale', self::PTD), 'awd_ogp[locale]', $_locales, $ogp->getLocale(), 'span4', array('class' => 'span2'));
    ?>
</div>
<div class="row">
    <?php
    //Determiners
    $_determiners = array(
        array('value' => 'auto', 'label' => __('Auto', self::PTD)),
        array('value' => 'a', 'label' => __('A', self::PTD)),
        array('value' => 'an', 'label' => __('An', self::PTD)),
        array('value' => 'the', 'label' => __('The', self::PTD))
    );
    echo $form->addSelect(__('The determiner', self::PTD), 'awd_ogp[determiner]', $_determiners, $ogp->getDeterminer(), 'span2', array('class' => 'span2'));

    //title of the page
    echo $form->addInputText(__('Title', self::PTD), 'awd_ogp[title]', $ogp->getTitle(), 'span4', array('class' => 'span4'));
    ?>
</div>
<div class="row">
    <?php
    //type
    $types = $ogp->supported_types(true);
    $types[] = 'custom';
    foreach ($types as $type) {
        $options[] = array('value' => $type, 'label' => ucfirst($type));
    }
    echo $form->addSelect(__('Type', self::PTD), 'awd_ogp[type]', $options, $object['type'], 'span2', array('class' => 'span2'));
    echo $form->addInputText(__('Custom object type', self::PTD), 'awd_ogp[custom_type]', $object['custom_type'], 'span3 dn depend_opengraph_custom_type', array('class' => 'span3'));
    ?>
</div>
<div class="row">
    <?php
//Description
    echo $form->addInputText('Description', 'awd_ogp[description]', $ogp->getDescription(), 'span6', array('class' => 'span6'));
    ?>
</div>
<div class="row">
    <?php
    //Site name
    echo $form->addInputText('Site Name', 'awd_ogp[site_name]', $ogp->getSiteName(), 'span4', array('class' => 'span4'));
    //Url
    echo $form->addInputText('Url', 'awd_ogp[url]', $ogp->getUrl(), 'span4', array('class' => 'span4'));
    ?>
</div>
<h1><?php _e('2. Add Media to Object', self::PTD); ?></h1>
<h2><?php _e('Images', self::PTD); ?> <button class="btn btn-mini awd_add_media_field" data-label="<?php _e('Image url', self::PTD); ?>" data-label2="<?php _e('Upload an Image', self::PTD); ?>" data-type="image" data-name="awd_ogp[images][]"><i class="icon-picture"></i><?php _e('Add a custom image', self::PTD); ?></button></h2>
<div class="row">
    <?php
    echo $form->addSelect(__('Auto load images attachments ?', self::PTD), 'awd_ogp[auto_load_images_attachment]', array(
        array('value' => 0, 'label' => __('No', self::PTD)),
        array('value' => 1, 'label' => __('Yes', self::PTD))
            ), $object['auto_load_images_attachment'], 'span3', array('class' => 'span2'));
    ?>
</div>
<div class="row">
    <div class="awd_ogp_fields_image">
        <?php
        if (!isset($object['images']))
            $object['images'] = array();
        $images = $object['images'];
        if (count($images)) {
            echo $form->addMediaButton('Image url', 'awd_ogp[images][]', $images[0], 'span8', array('class' => 'span6'), array('data-title' => __('Upload an Image', self::PTD), 'data-type' => 'image'), false);
            unset($images[0]);
            foreach ($images as $image) {
                echo $form->addMediaButton('Image url', 'awd_ogp[images][]', $image, 'span8', array('class' => 'span6'), array('data-title' => __('Upload an Image', self::PTD), 'data-type' => 'image'), true);
            }
        } else {
            echo $form->addMediaButton('Image url', 'awd_ogp[images][]', '', 'span8', array('class' => 'span6'), array('data-title' => __('Upload an Image', self::PTD), 'data-type' => 'image'), false);
        }
        ?>
    </div>
</div>
<h2><?php _e('Videos', self::PTD); ?> <button class="btn btn-mini awd_add_media_field" data-label="<?php _e('Video url', self::PTD); ?>" data-label2="<?php _e('Upload an Image', self::PTD); ?>" data-type="video" data-name="awd_ogp[videos][]"><i class="icon-film"></i> <?php _e('Add a custom video', self::PTD); ?></button></h2>
<div class="row">
    <?php
    echo $form->addSelect(__('Auto load videos attachments ?', self::PTD), 'awd_ogp[auto_load_videos_attachment]', array(
        array('value' => 0, 'label' => __('No', self::PTD)),
        array('value' => 1, 'label' => __('Yes', self::PTD))
            ), $object['auto_load_videos_attachment'], 'span3', array('class' => 'span2'));
    ?>
</div>
<div class="row">
    <div class="awd_ogp_fields_video">
        <?php
        if (!isset($object['videos']))
            $object['videos'] = array();
        $videos = $object['videos'];
        if (count($videos)) {
            echo $form->addMediaButton('Video url', 'awd_ogp[videos][]', $videos[0], 'span8', array('class' => 'span6'), array('data-title' => __('Upload a Video', self::PTD), 'data-type' => 'video'), false);
            unset($videos[0]);
            foreach ($videos as $video) {
                echo $form->addMediaButton('Video url', 'awd_ogp[videos][]', $video, 'span8', array('class' => 'span6'), array('data-title' => __('Upload a Video', self::PTD), 'data-type' => 'video'), true);
            }
        } else {
            echo $form->addMediaButton('Video url', 'awd_ogp[videos][]', '', 'span8', array('class' => 'span6'), array('data-title' => __('Upload a Video', self::PTD), 'data-type' => 'video'), false);
        }
        ?>
    </div>
</div>
<h2><?php _e('Audios', self::PTD); ?> <button class="btn btn-mini awd_add_media_field" data-label="<?php _e('Audio url', self::PTD); ?>" data-label2="<?php _e('Upload an Image', self::PTD); ?>" data-type="audio" data-name="awd_ogp[audios][]"><i class="icon-music"></i> <?php _e('Add a custom audio', self::PTD); ?></button></h2>
<div class="row">
    <?php
    echo $form->addSelect(__('Auto load audios attachments ?', self::PTD), 'awd_ogp[auto_load_audios_attachment]', array(
        array('value' => 0, 'label' => __('No', self::PTD)),
        array('value' => 1, 'label' => __('Yes', self::PTD))
            ), $object['auto_load_audios_attachment'], 'span3', array('class' => 'span2'));
    ?>
</div>
<div class="row">
    <div class="awd_ogp_fields_audio">
        <?php
        if (!isset($object['audios']))
            $object['audios'] = array();
        $audios = $object['audios'];
        if (count($audios)) {
            echo $form->addMediaButton('Audio url', 'awd_ogp[audios][]', $audios[0], 'span8', array('class' => 'span6'), array('data-title' => __('Upload Audio', self::PTD), 'data-type' => 'audio'), false);
            unset($audios[0]);
            foreach ($audios as $audio) {
                echo $form->addMediaButton('Audio url', 'awd_ogp[audios][]', $audio, 'span8', array('class' => 'span6'), array('data-title' => __('Upload Audio', self::PTD), 'data-type' => 'audio'), true);
            }
        } else {
            echo $form->addMediaButton('Audio url', 'awd_ogp[audios][]', '', 'span8', array('class' => 'span6'), array('data-title' => __('Upload Audio', self::PTD), 'data-type' => 'audio'), false);
        }
        ?>
    </div>
</div>
<?php if (!is_array($objectId)) { ?>
    <div class="form-actions">
        <div class="btn-group pull-right">
            <button class="btn btn-primary awd_submit_ogp"><i class="icon-ok icon-white"></i> <?php _e('Save this object', self::PTD); ?></button>
            <button class="btn btn-danger pull-right hide_ogp_form"><i class="icon-remove icon-white"></i> <?php _e('Cancel', self::PTD); ?></button>
        </div>
    </div>
    <?php wp_nonce_field(self::PLUGIN_SLUG . '_save_ogp_object', self::OPTION_PREFIX . '_nonce_options_save_ogp_object'); ?>
    <?php
    echo $form->end();
}