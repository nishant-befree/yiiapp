<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model pbackend\modules\practice\models\Practices */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="practices-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'pr_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'parent_id')->textInput() ?>

    <?= $form->field($model, 'practices_user_type')->textInput() ?>

    <?= $form->field($model, 'role')->textInput() ?>

    <?= $form->field($model, 'type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'firm_trading')->textInput() ?>

    <?= $form->field($model, 'company_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'company_logo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'company_phone_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'firstname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'lastname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'practice_image')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'website')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bdm_person')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sales_person')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sr_manager')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'india_manager')->textInput() ?>

    <?= $form->field($model, 'hod_person')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'smsf_crm_person')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'paraplanning_crm_person')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'business_service_crm_person')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tms_crm_person')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'unit_building_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'street_adress')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'suburb')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'state')->textInput() ?>

    <?= $form->field($model, 'country')->textInput() ?>

    <?= $form->field($model, 'postcode')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'postal_address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'main_contact_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'other_contact_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'alternate_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fax')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'software')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'business_service_software')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tax_software')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'soa_software')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'comp_projected')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'audit_projected')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'date_signed_up')->textInput() ?>

    <?= $form->field($model, 'agreed_services')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sent_items')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sent_time')->textInput() ?>

    <?= $form->field($model, 'billing_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'billing_email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'billing_phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'billing_payment_method')->textInput() ?>

    <?= $form->field($model, 'billing_signed_agreement')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'billing_additional_info')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'assigned_users')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'is_password_changed')->textInput() ?>

    <?= $form->field($model, 'is_active')->textInput() ?>

    <?= $form->field($model, 'is_sr_license')->textInput() ?>

    <?= $form->field($model, 'is_argeed_resource_model')->dropDownList([ 'Y' => 'Y', 'N' => 'N', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'part_or_full_time')->dropDownList([ 'P' => 'P', 'F' => 'F', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'parttime_booking')->dropDownList([ 'MW' => 'MW', 'WF' => 'WF', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'specific_report_package')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'add_to_classsuper')->textInput() ?>

    <?= $form->field($model, 'is_dailyprocessing_required')->textInput() ?>

    <?= $form->field($model, 'dailyprocessing_frequency')->textInput() ?>

    <?= $form->field($model, 'frequency_of_raising_query')->textInput() ?>

    <?= $form->field($model, 'custom_compliance_report_required')->textInput() ?>

    <?= $form->field($model, 'custom_compliance_report_budgeted_hours')->textInput() ?>

    <?= $form->field($model, 'send_signed_agreement_to_billingteam')->textInput() ?>

    <?= $form->field($model, 'send_direct_debit_form')->textInput() ?>

    <?= $form->field($model, 'postaladress_sameas_streedaddress')->textInput() ?>

    <?= $form->field($model, 'postal_pobox_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'postal_suburb')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'postal_state')->textInput() ?>

    <?= $form->field($model, 'postal_country')->textInput() ?>

    <?= $form->field($model, 'postal_postcode')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'is_draft')->textInput() ?>

    <?= $form->field($model, 'is_data_activated_in_class')->textInput() ?>

    <?= $form->field($model, 'is_billing_completed_by_sales')->textInput() ?>

    <?= $form->field($model, 'is_billing_completed_by_billing_team')->textInput() ?>

    <?= $form->field($model, 'paraplanning_liencee')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'paraplannning_practice_abn')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'created_on')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
