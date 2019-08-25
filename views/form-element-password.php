<?php
/**
 * File: views/form-element-password.php
 *
 * Template partial for Password fields.
 *
 * Example:
 * --- php
 * 'fieldname' => array(
 *   'type' => 'password',
 *   'label' => __('Field label', 'text-domain'),
 *   size' => 10,
 *   'tip' => __('Helper text', 'text-domain')
 * )
 * ---
 */

echo $label_start; ?>
	<label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $label ); ?>:</label>
<?php echo $label_end; ?>

<?php echo $field_start; ?>
	<input type="password" name="<?php echo esc_attr( $name ); ?>" id="<?php echo esc_attr( $id ); ?>" value="<?php echo esc_attr( $value ); ?>" size="<?php echo esc_attr( $size ); ?>" class="<?php echo esc_attr( $classname ); ?>" aria-describedby="<?php echo esc_attr( $id ); ?>-tip">
	<<?php echo $tip_element; ?> class="description" id="<?php echo esc_attr( $id ); ?>-tip">
		<?php echo $tip; ?>
	</<?php echo $tip_element; ?>>
<?php echo $field_end; ?>
