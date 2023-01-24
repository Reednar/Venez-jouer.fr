<!-- Modal pour profil temporaire pour jouer -->
<div class="modal fade" tabindex="-1" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Profile temporaire</h5>
      </div>
      <form action="index.php?uc=authentification&action=validationProfileTemp" method="post">
        <div class="modal-body">
          <div class="mb-3">
            <label for="pseudo" class="col-form-label">Pseudo:</label>
            <input type="text" class="form-control" id="pseudo" name="pseudo" placeholder="Pseudo temporaire" maxlength="10">
          </div>
        </div>
        <div class="modal-footer">
          <a type="button" class="btn btn-secondary" href="index.php?uc=accueil">Annuler</a>
          <button type="submit" class="btn btn-primary">Sauvegarder</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    $("#myModal").modal('show');
  });

  var myModal = document.getElementById('myModal');
  var modal = new bootstrap.Modal(myModal, {
    backdrop: 'static',
    keyboard: false
  });
</script>