<?php
$collection = Mage::getModel('admin/user')->getCollection();

foreach ($collection as $user) {
    $model = Mage::getModel('fsgoogletwofa/admin_hash')
        ->loadByAdmin($user);

    if (!$model->getId()) {
        $secret = Mage::getModel('fsgoogletwofa/admin_factory')->generateSecretKey();
        $model->setHash($secret)->setMode(1)->save();
    }
}
