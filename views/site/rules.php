<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = 'Правила';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="d-inline-block mt-3">
        <h3>1. Ограничение на отправку сообщений:</h3>
        <ul>
            <li>Пользователь может отправить сообщение только раз в 3 минуты.</li>
            <li>Если пользователь попытается отправить сообщение раньше, чем через 3 минуты после предыдущего сообщения,
                ему будет выдана ошибка с информацией о времени, когда он сможет отправить следующее сообщение.
            </li>
        </ul>

        <h3>2. Ограничение на реакции (эмодзи):</h3>
        <ul>
            <li>Один IP-адрес может оставить только одну реакцию на один пост.</li>
            <li>Если пользователь попытается оставить вторую реакцию на тот же пост, предыдущая реакция будет заменена на новую.
            </li>
            <li>Если пользователь повторно выбирает реакцию, которую он уже оставил ранее, данная реакция будет удалена.
            </li>
        </ul>
    </div>
</div>
