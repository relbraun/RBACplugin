<?php
/* @var $roles array */
/* @var $post WP_post */
/* @var $this RBACplugin */
/* @var $selected_posts array */

?>
<table class="widefat">
    <tr>
<th colspan="3">
    <?php _e('Select who <b>cannot</b> read this post', self::DOMAIN); ?>
</th></tr>
<!--
<div class="RBAC"><?php //var_dump($selected_posts);die; ?>
<?php

foreach ($roles as $role => $caps){
    $checked=  in_array($role, $selected_posts)?' checked':'';
    echo "<label rel='$role'>";
    echo "<input type='checkbox' name='RBACroles[$role][role]' id='$role' value='$role'$checked/>$role<label>\n"; ?>
<?php } ?>

        <p>What do you want to do with these roles?</p>
        <select name="RBACroles[roles][todo]" id="RBAC_roles_todo">
                    <?php $this->renderOptions($this->non_logged_options,$selected_posts['roles']['todo']); ?>
    </select>
<select name="RBACroles[roles][redirect]" id="RBAC_roles_redirect">
                    <?php $this->renderOptions($this->logged_options,$selected_posts['roles']['redirect']); ?>
    </select>

</div>
-->
    <tr class='RBAC not-logged'>


  <td>  <label rel='not_logged'>
    <input type='checkbox' name='RBACroles[role]' id='not_logged' value='not_logged'<?php echo $checked ?>/>Not logged-in user<label></td>
<td>
    <select name="RBACroles[todo]" id="RBAC_not_logged_todo">
                    <?php $this->renderOptions($this->non_logged_options,$selected_posts['todo']); ?>
    </td>
    <td>
    </select>
<select name="RBACroles[redirect]" id="RBAC_not_logged_redirect">
                    <?php $this->renderOptions($this->get_pages(),$selected_posts['todo']); ?>
    </select></td>

</tr>
<tr class='RBAC logged'>

<?php
    echo "<td><label rel='logged'>";
    echo "<input type='checkbox' name='RBACroles[role]' id='logged' value='logged'$checked/> Logged-in user<label></td>\n"; ?>
<td>
    <select name="RBACroles[todo]" id="RBAC_logged_todo">
                    <?php $this->renderOptions($this->logged_options,$selected_posts['todo']); ?>
    </select></td>
    <td>
<select name="RBACroles[redirect]" id="RBAC_logged_redirect">
                    <?php $this->renderOptions($this->get_pages(),$this->$selected_posts['todo']); ?>
    </select></td>

</tr>
</table>
<div class="clear"></div>