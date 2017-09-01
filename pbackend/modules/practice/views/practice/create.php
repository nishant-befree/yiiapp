<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model pbackend\modules\practice\models\Practices */

$this->title = 'Create Practices';
$this->params['breadcrumbs'][] = ['label' => 'Practices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="practices-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
