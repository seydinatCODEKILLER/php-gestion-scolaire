<?php

/**
 * Composant Modal réutilisable
 * 
 * @param string $id - ID du modal
 * @param string $title - Titre du modal
 * @param string $content - Contenu HTML du modal
 * @param string $actionText - Texte du bouton d'action (optionnel)
 * @param string $actionUrl - URL de l'action (optionnel)
 */
function renderModal($id, $title, $content, $actionText = null, $actionUrl = null)
{
?>
    <dialog id="<?= $id ?>" class="modal">
        <div class="modal-box w-11/12 max-w-5xl">
            <h3 class="font-bold text-lg"><?= $title ?></h3>
            <div class="py-4">
                <?= $content ?>
            </div>
            <div class="modal-action">
                <?php if ($actionText && $actionUrl): ?>
                    <form method="POST" action="<?= $actionUrl ?>">
                        <button type="submit" class="btn btn-primary"><?= $actionText ?></button>
                    </form>
                <?php endif; ?>
                <button onclick="document.getElementById('<?= $id ?>').close()" class="btn btn-ghost">
                    Fermer
                </button>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>
<?php
}
