<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "practices".
 *
 * @property integer $id
 * @property string $pr_code
 * @property integer $parent_id
 * @property integer $practices_user_type
 * @property integer $role
 * @property string $type
 * @property integer $firm_trading
 * @property string $company_name
 * @property string $company_logo
 * @property string $company_phone_no
 * @property string $email
 * @property string $password
 * @property string $firstname
 * @property string $lastname
 * @property string $practice_image
 * @property string $website
 * @property string $bdm_person
 * @property string $sales_person
 * @property string $sr_manager
 * @property integer $india_manager
 * @property string $hod_person
 * @property string $smsf_crm_person
 * @property string $paraplanning_crm_person
 * @property string $business_service_crm_person
 * @property string $tms_crm_person
 * @property string $unit_building_number
 * @property string $street_adress
 * @property string $suburb
 * @property integer $state
 * @property integer $country
 * @property string $postcode
 * @property string $postal_address
 * @property string $main_contact_name
 * @property string $other_contact_name
 * @property string $phone_no
 * @property string $alternate_no
 * @property string $fax
 * @property string $software
 * @property string $business_service_software
 * @property string $tax_software
 * @property string $soa_software
 * @property string $comp_projected
 * @property string $audit_projected
 * @property string $date_signed_up
 * @property string $agreed_services
 * @property string $sent_items
 * @property string $sent_time
 * @property string $billing_name
 * @property string $billing_email
 * @property string $billing_phone
 * @property integer $billing_payment_method
 * @property string $billing_signed_agreement
 * @property string $billing_additional_info
 * @property string $assigned_users
 * @property integer $is_password_changed
 * @property integer $is_active
 * @property integer $is_sr_license
 * @property string $is_argeed_resource_model
 * @property string $part_or_full_time
 * @property string $parttime_booking
 * @property string $specific_report_package
 * @property integer $add_to_classsuper
 * @property integer $is_dailyprocessing_required
 * @property integer $dailyprocessing_frequency
 * @property integer $frequency_of_raising_query
 * @property integer $custom_compliance_report_required
 * @property integer $custom_compliance_report_budgeted_hours
 * @property integer $send_signed_agreement_to_billingteam
 * @property integer $send_direct_debit_form
 * @property integer $postaladress_sameas_streedaddress
 * @property string $postal_pobox_no
 * @property string $postal_suburb
 * @property integer $postal_state
 * @property integer $postal_country
 * @property string $postal_postcode
 * @property integer $is_draft
 * @property integer $is_data_activated_in_class
 * @property integer $is_billing_completed_by_sales
 * @property integer $is_billing_completed_by_billing_team
 * @property string $paraplanning_liencee
 * @property string $paraplannning_practice_abn
 * @property integer $created_by
 * @property string $created_on
 */
class Practices extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'practices';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pr_code', 'email', 'india_manager', 'software', 'comp_projected', 'audit_projected', 'sent_time'], 'required'],
            [['parent_id', 'practices_user_type', 'role', 'firm_trading', 'india_manager', 'state', 'country', 'billing_payment_method', 'is_password_changed', 'is_active', 'is_sr_license', 'add_to_classsuper', 'is_dailyprocessing_required', 'dailyprocessing_frequency', 'frequency_of_raising_query', 'custom_compliance_report_required', 'custom_compliance_report_budgeted_hours', 'send_signed_agreement_to_billingteam', 'send_direct_debit_form', 'postaladress_sameas_streedaddress', 'postal_state', 'postal_country', 'is_draft', 'is_data_activated_in_class', 'is_billing_completed_by_sales', 'is_billing_completed_by_billing_team', 'created_by'], 'integer'],
            [['date_signed_up', 'sent_time', 'created_on'], 'safe'],
            [['billing_additional_info', 'is_argeed_resource_model', 'part_or_full_time', 'parttime_booking'], 'string'],
            [['pr_code'], 'string', 'max' => 5],
            [['type', 'bdm_person', 'sales_person', 'sr_manager', 'hod_person', 'smsf_crm_person', 'paraplanning_crm_person', 'business_service_crm_person', 'tms_crm_person', 'software', 'comp_projected', 'audit_projected', 'assigned_users', 'specific_report_package'], 'string', 'max' => 255],
            [['company_name', 'company_logo', 'company_phone_no', 'firstname', 'lastname', 'practice_image', 'postcode', 'phone_no', 'alternate_no', 'fax', 'agreed_services', 'sent_items', 'billing_name', 'billing_email', 'billing_phone', 'billing_signed_agreement', 'postal_pobox_no'], 'string', 'max' => 100],
            [['email', 'password', 'website', 'unit_building_number', 'street_adress', 'suburb', 'postal_address', 'main_contact_name', 'other_contact_name', 'postal_suburb'], 'string', 'max' => 200],
            [['business_service_software', 'tax_software', 'soa_software', 'paraplanning_liencee', 'paraplannning_practice_abn'], 'string', 'max' => 50],
            [['postal_postcode'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pr_code' => 'Pr Code',
            'parent_id' => 'Parent ID',
            'practices_user_type' => 'Practices User Type',
            'role' => 'Role',
            'type' => 'Type',
            'firm_trading' => 'Firm Trading',
            'company_name' => 'Company Name',
            'company_logo' => 'Company Logo',
            'company_phone_no' => 'Company Phone No',
            'email' => 'Email',
            'password' => 'Password',
            'firstname' => 'Firstname',
            'lastname' => 'Lastname',
            'practice_image' => 'Practice Image',
            'website' => 'Website',
            'bdm_person' => 'Bdm Person',
            'sales_person' => 'Sales Person',
            'sr_manager' => 'Sr Manager',
            'india_manager' => 'India Manager',
            'hod_person' => 'Hod Person',
            'smsf_crm_person' => 'Smsf Crm Person',
            'paraplanning_crm_person' => 'Paraplanning Crm Person',
            'business_service_crm_person' => 'Business Service Crm Person',
            'tms_crm_person' => 'Tms Crm Person',
            'unit_building_number' => 'Unit Building Number',
            'street_adress' => 'Street Adress',
            'suburb' => 'Suburb',
            'state' => 'State',
            'country' => 'Country',
            'postcode' => 'Postcode',
            'postal_address' => 'Postal Address',
            'main_contact_name' => 'Main Contact Name',
            'other_contact_name' => 'Other Contact Name',
            'phone_no' => 'Phone No',
            'alternate_no' => 'Alternate No',
            'fax' => 'Fax',
            'software' => 'Software',
            'business_service_software' => 'Business Service Software',
            'tax_software' => 'Tax Software',
            'soa_software' => 'Soa Software',
            'comp_projected' => 'Comp Projected',
            'audit_projected' => 'Audit Projected',
            'date_signed_up' => 'Date Signed Up',
            'agreed_services' => 'Agreed Services',
            'sent_items' => 'Sent Items',
            'sent_time' => 'Sent Time',
            'billing_name' => 'Billing Name',
            'billing_email' => 'Billing Email',
            'billing_phone' => 'Billing Phone',
            'billing_payment_method' => 'Billing Payment Method',
            'billing_signed_agreement' => 'Billing Signed Agreement',
            'billing_additional_info' => 'Billing Additional Info',
            'assigned_users' => 'Assigned Users',
            'is_password_changed' => 'Is Password Changed',
            'is_active' => 'Is Active',
            'is_sr_license' => 'Is Sr License',
            'is_argeed_resource_model' => 'Is Argeed Resource Model',
            'part_or_full_time' => 'Part Or Full Time',
            'parttime_booking' => 'Parttime Booking',
            'specific_report_package' => 'Specific Report Package',
            'add_to_classsuper' => 'Add To Classsuper',
            'is_dailyprocessing_required' => 'Is Dailyprocessing Required',
            'dailyprocessing_frequency' => 'Dailyprocessing Frequency',
            'frequency_of_raising_query' => 'Frequency Of Raising Query',
            'custom_compliance_report_required' => 'Custom Compliance Report Required',
            'custom_compliance_report_budgeted_hours' => 'Custom Compliance Report Budgeted Hours',
            'send_signed_agreement_to_billingteam' => 'Send Signed Agreement To Billingteam',
            'send_direct_debit_form' => 'Send Direct Debit Form',
            'postaladress_sameas_streedaddress' => 'Postaladress Sameas Streedaddress',
            'postal_pobox_no' => 'Postal Pobox No',
            'postal_suburb' => 'Postal Suburb',
            'postal_state' => 'Postal State',
            'postal_country' => 'Postal Country',
            'postal_postcode' => 'Postal Postcode',
            'is_draft' => 'Is Draft',
            'is_data_activated_in_class' => 'Is Data Activated In Class',
            'is_billing_completed_by_sales' => 'Is Billing Completed By Sales',
            'is_billing_completed_by_billing_team' => 'Is Billing Completed By Billing Team',
            'paraplanning_liencee' => 'Paraplanning Liencee',
            'paraplannning_practice_abn' => 'Paraplannning Practice Abn',
            'created_by' => 'Created By',
            'created_on' => 'Created On',
        ];
    }
}
