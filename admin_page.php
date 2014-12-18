<?php

/* @var $this RBACplugin */

?>
<style>
    select.active{
        display: inline-block;
    }
    select{
        display: none;
    }
</style>
<div class="wrap">
    <h2>
        <?php _e('RBAC Settings', self::DOMAIN); ?>
    </h2>
    <form method="post">
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><label rel="RBAC_posttypes"><?php _e('Select post types you want to restrict:'); ?></label></th>
                <td><?php
                $checked='';
                   foreach(get_post_types() as $post_type){
                       if($this->selectedPostTypes)
                            $checked=  in_array($post_type, $this->selectedPostTypes) ? ' checked' : '';
                       echo "<p><input type='checkbox' name='RBAC_settings[post_type][]' id='RBAC_posttypes' value='$post_type'$checked/> $post_type</p>\n";
                   }
                ?></td>
            </tr>
            <tr>
                <th><?php _e('What non-logged user should do?', self::DOMAIN); ?></th>
                <td>
                    <select name="RBAC_settings[non_logged_does]" id="RBAC_non_logged_does" class="active">
                    <?php $this->renderOptions($this->non_logged_options,$this->get_option('non_logged_does')); ?>
                   </select>

                    <select name="RBAC_settings[non_logged_redirect]" id="RBAC_non_logged_redirect" class="<?php $this->get_option('non_logged_does')=='redirect' ? 'active':''; ?>">
                    <?php $this->renderOptions($this->get_pages(),$this->get_option('non_logged_redirect')); ?>
                   </select>
                    </td>
            </tr>
            <tr valign="top">
                <th><input type="submit" class="button-primary" value="<?php _e('Save', self::DOMAIN); ?>" /></th>

            </tr>
        </table>
    </form>
</div>
<script>
    (function($){
        $('#RBAC_non_logged_does').change(function(){
            if($(this).val()=='redirect')
                $(this).next('select').addClass('active');
            else
                $(this).next('select').removeClass('active');
        });
    })(jQuery);
    </script>