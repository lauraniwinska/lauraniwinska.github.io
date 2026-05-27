<?php
    /** @var $post ?\App\Model\Music */
?>

<div class="form-group">
    <label for="subject">Title</label>
    <input type="text" id="subject" name="music[subject]" value="<?= $music ? $music->getSubject() : '' ?>">
</div>

<div class="form-group">
    <label for="content">Content</label>
    <textarea id="content" name="music[content]"><?= $music? $music->getContent() : '' ?></textarea>
</div>

<div class="form-group">
    <label></label>
    <input type="submit" value="Submit">
</div>
