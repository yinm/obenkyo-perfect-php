<?php $this->setLayoutVar('title', 'アカウント登録') ?>

<h2>アカウント登録</h2>

<form action="<?php echo $base_url; ?>/account/register" method="post">
  <input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>">

  <table>
    <tbody>
    <tr>
      <th>ユーザID</th>
      <td>
        <input type="text" name="user_name" value="">
      </td>
    </tr>
    <tr>
      <th>パスワード</th>
      <td>
        <input type="password" name="password" value="">
      </td>
    </tr>
    </tbody>
  </table>

  <p>
    <input type="submit" value="登録">
  </p>
</form>