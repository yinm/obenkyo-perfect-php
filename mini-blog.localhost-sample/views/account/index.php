<?php $this->setLayoutvar('title', 'アカウント'); ?>

<h2>アカウント</h2>

<p>
    ユーザID:
    <a href="<?php echo $base_urlp ?>/user/<?php echo $this->escape($user['user_name']); ?>">
        <strong><?php echo $this->escape($user['user_name']); ?></strong>
    </a>
</p>

<ul>
  <li>
    <a href="<?php echo $base_url; ?>">ホーム</a>
  </li>
  <li>
    <a href="<?php echo $base_url; ?>/account/signout">ログアウト</a>
  </li>
</ul>
