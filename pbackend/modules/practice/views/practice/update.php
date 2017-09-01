<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model pbackend\modules\practice\models\Practices */

$this->title = 'Update Practices: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Practices', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="practices-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
