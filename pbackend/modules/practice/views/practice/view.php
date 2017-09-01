<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model pbackend\modules\practice\models\Practices */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Practices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="practices-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'pr_code',
            'parent_id',
            'practices_user_type',
            'role',
            'type',
            'firm_trading',
            'company_name',
            'company_logo',
            'company_phone_no',
            'email:email',
            'password',
            'firstname',
            'lastname',
            'practice_image',
            'website',
            'bdm_person',
            'sales_person',
            'sr_manager',
            'india_manager',
            'hod_person',
            'smsf_crm_person',
            'paraplanning_crm_person',
            'business_service_crm_person',
            'tms_crm_person',
            'unit_building_number',
            'street_adress',
            'suburb',
            'state',
            'country',
            'postcode',
            'postal_address',
            'main_contact_name',
            'other_contact_name',
            'phone_no',
            'alternate_no',
            'fax',
            'software',
            'business_service_software',
            'tax_software',
            'soa_software',
            'comp_projected',
            'audit_projected',
            'date_signed_up',
            'agreed_services',
            'sent_items',
            'sent_time',
            'billing_name',
            'billing_email:email',
            'billing_phone',
            'billing_payment_method',
            'billing_signed_agreement',
            'billing_additional_info:ntext',
            'assigned_users',
            'is_password_changed',
            'is_active',
            'is_sr_license',
            'is_argeed_resource_model',
            'part_or_full_time',
            'parttime_booking',
            'specific_report_package',
            'add_to_classsuper',
            'is_dailyprocessing_required',
            'dailyprocessing_frequency',
            'frequency_of_raising_query',
            'custom_compliance_report_required',
            'custom_compliance_report_budgeted_hours',
            'send_signed_agreement_to_billingteam',
            'send_direct_debit_form',
            'postaladress_sameas_streedaddress',
            'postal_pobox_no',
            'postal_suburb',
            'postal_state',
            'postal_country',
            'postal_postcode',
            'is_draft',
            'is_data_activated_in_class',
            'is_billing_completed_by_sales',
            'is_billing_completed_by_billing_team',
            'paraplanning_liencee',
            'paraplannning_practice_abn',
            'created_by',
            'created_on',
        ],
    ]) ?>

</div>
