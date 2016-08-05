<div class="container-fluid userCertDetail">
    <div class="row">
        <div class="col-xs-12">
            <img src="<?= $this->imgSrc ?>" alt="<?= $this->imgAlt ?>" width="<?= $this->imgWidth ?>" height="<?= $this->imgHeight ?>" title="<?= $this->imgTitle ?>" />
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <?= $this->certTitle ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <?= $this->certScore ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <?= $this->certDateTime ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <button class="btn btn-info">View</button>
        </div>
    </div>
</div>
