<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model pbackend\modules\practice\models\PracticesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="practices-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'pr_code') ?>

    <?= $form->field($model, 'parent_id') ?>

    <?= $form->field($model, 'practices_user_type') ?>

    <?= $form->field($model, 'role') ?>

    <?php // echo $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'firm_trading') ?>

    <?php // echo $form->field($model, 'company_name') ?>

    <?php // echo $form->field($model, 'company_logo') ?>

    <?php // echo $form->field($model, 'company_phone_no') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'password') ?>

    <?php // echo $form->field($model, 'firstname') ?>

    <?php // echo $form->field($model, 'lastname') ?>

    <?php // echo $form->field($model, 'practice_image') ?>

    <?php // echo $form->field($model, 'website') ?>

    <?php // echo $form->field($model, 'bdm_person') ?>

    <?php // echo $form->field($model, 'sales_person') ?>

    <?php // echo $form->field($model, 'sr_manager') ?>

    <?php // echo $form->field($model, 'india_manager') ?>

    <?php // echo $form->field($model, 'hod_person') ?>

    <?php // echo $form->field($model, 'smsf_crm_person') ?>

    <?php // echo $form->field($model, 'paraplanning_crm_person') ?>

    <?php // echo $form->field($model, 'business_service_crm_person') ?>

    <?php // echo $form->field($model, 'tms_crm_person') ?>

    <?php // echo $form->field($model, 'unit_building_number') ?>

    <?php // echo $form->field($model, 'street_adress') ?>

    <?php // echo $form->field($model, 'suburb') ?>

    <?php // echo $form->field($model, 'state') ?>

    <?php // echo $form->field($model, 'country') ?>

    <?php // echo $form->field($model, 'postcode') ?>

    <?php // echo $form->field($model, 'postal_address') ?>

    <?php // echo $form->field($model, 'main_contact_name') ?>

    <?php // echo $form->field($model, 'other_contact_name') ?>

    <?php // echo $form->field($model, 'phone_no') ?>

    <?php // echo $form->field($model, 'alternate_no') ?>

    <?php // echo $form->field($model, 'fax') ?>

    <?php // echo $form->field($model, 'software') ?>

    <?php // echo $form->field($model, 'business_service_software') ?>

    <?php // echo $form->field($model, 'tax_software') ?>

    <?php // echo $form->field($model, 'soa_software') ?>

    <?php // echo $form->field($model, 'comp_projected') ?>

    <?php // echo $form->field($model, 'audit_projected') ?>

    <?php // echo $form->field($model, 'date_signed_up') ?>

    <?php // echo $form->field($model, 'agreed_services') ?>

    <?php // echo $form->field($model, 'sent_items') ?>

    <?php // echo $form->field($model, 'sent_time') ?>

    <?php // echo $form->field($model, 'billing_name') ?>

    <?php // echo $form->field($model, 'billing_email') ?>

    <?php // echo $form->field($model, 'billing_phone') ?>

    <?php // echo $form->field($model, 'billing_payment_method') ?>

    <?php // echo $form->field($model, 'billing_signed_agreement') ?>

    <?php // echo $form->field($model, 'billing_additional_info') ?>

    <?php // echo $form->field($model, 'assigned_users') ?>

    <?php // echo $form->field($model, 'is_password_changed') ?>

    <?php // echo $form->field($model, 'is_active') ?>

    <?php // echo $form->field($model, 'is_sr_license') ?>

    <?php // echo $form->field($model, 'is_argeed_resource_model') ?>

    <?php // echo $form->field($model, 'part_or_full_time') ?>

    <?php // echo $form->field($model, 'parttime_booking') ?>

    <?php // echo $form->field($model, 'specific_report_package') ?>

    <?php // echo $form->field($model, 'add_to_classsuper') ?>

    <?php // echo $form->field($model, 'is_dailyprocessing_required') ?>

    <?php // echo $form->field($model, 'dailyprocessing_frequency') ?>

    <?php // echo $form->field($model, 'frequency_of_raising_query') ?>

    <?php // echo $form->field($model, 'custom_compliance_report_required') ?>

    <?php // echo $form->field($model, 'custom_compliance_report_budgeted_hours') ?>

    <?php // echo $form->field($model, 'send_signed_agreement_to_billingteam') ?>

    <?php // echo $form->field($model, 'send_direct_debit_form') ?>

    <?php // echo $form->field($model, 'postaladress_sameas_streedaddress') ?>

    <?php // echo $form->field($model, 'postal_pobox_no') ?>

    <?php // echo $form->field($model, 'postal_suburb') ?>

    <?php // echo $form->field($model, 'postal_state') ?>

    <?php // echo $form->field($model, 'postal_country') ?>

    <?php // echo $form->field($model, 'postal_postcode') ?>

    <?php // echo $form->field($model, 'is_draft') ?>

    <?php // echo $form->field($model, 'is_data_activated_in_class') ?>

    <?php // echo $form->field($model, 'is_billing_completed_by_sales') ?>

    <?php // echo $form->field($model, 'is_billing_completed_by_billing_team') ?>

    <?php // echo $form->field($model, 'paraplanning_liencee') ?>

    <?php // echo $form->field($model, 'paraplannning_practice_abn') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'created_on') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
