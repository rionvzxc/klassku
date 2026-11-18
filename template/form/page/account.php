<?php function TemplateAccountFormUpdatePassword($data) {
?>
<form xgrid-cf="cf2 a0 b1" vstyle="gp-10 al-i5" action="update-password" method="POST">
    <div>Password Lama : </div>
    <input type="password" name="old_password">
    <div>Password Baru : </div>
    <input type="password" name="new_password">
    <div></div>
    <button>Ubah</button>
</form>
<?php } ?>