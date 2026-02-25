<?php


if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class OKO_Kitchensink_WooCommerce {

    private $oko_wc_fields = [
        'phone_number' => [
            'label'    => 'Phone Number',
            'type'     => 'tel',
            'classes'  => 'woocommerce-Input woocommerce-Input--text input-text',
            'required' => false, // Add validation later
        ],
        'company_name' => [
            'label'    => 'Company Name',
            'type'     => 'text',
            'classes'  => 'woocommerce-Input woocommerce-Input--text input-text',
            'required' => false,
        ],
        // Add more: 'slug' => [ 'label' => '', 'type' => '', 'classes' => '' ]
    ];

    public function __construct() {
        add_action( 'woocommerce_edit_account_form', [ $this, 'add_custom_field_to_edit_account' ] );
        add_action( 'woocommerce_save_account_details', [ $this, 'save_custom_fields' ] );
    }

    /**
     * Get extensible fields (filterable)
     */
    public function get_fields() {
        $fields = apply_filters( 'oko_wc_account_fields', $this->oko_wc_fields );
        return $fields;
    }

    public function add_custom_field_to_edit_account() {
        $user_id = get_current_user_id();
        $fields  = $this->get_fields();

        foreach ( $fields as $slug => $field ) {
            $value = get_user_meta( $user_id, "oko_ksk_{$slug}", true );
            $required_attr = ( $field['required'] ?? false ) ? ' required' : '';
            ?>
            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <label for="<?php echo esc_attr( $slug ); ?>">
                    <?php echo esc_html( $field['label'] ); ?>
                    <?php if ( $field['required'] ?? false ) echo '<span class="required">*</span>'; ?>
                </label>
                <input 
                    type="<?php echo esc_attr( $field['type'] ); ?>" 
                    class="<?php echo esc_attr( $field['classes'] ?? 'input-text' ); ?>" 
                    name="<?php echo esc_attr( $slug ); ?>" 
                    id="<?php echo esc_attr( $slug ); ?>" 
                    value="<?php echo esc_attr( $value ); ?>" 
                    <?php echo $required_attr; ?>
                />
            </p>
            <?php
        }
    }

    public function save_custom_fields( $user_id ) {
        $fields = $this->get_fields();
        foreach ( $fields as $slug => $field ) {
            if ( isset( $_POST[ $slug ] ) ) {
                $value = sanitize_text_field( $_POST[ $slug ] );
                update_user_meta( $user_id, "oko_ksk_{$slug}", $value );
            }
        }
    }
}

new OKO_Kitchensink_WooCommerce();
