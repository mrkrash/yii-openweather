<div class="weather">
	<div class="icon">
		<img src="<?= $this->getAssetsUrl() . '/img/' . $icon ?>" alt="<?= substr($icon, 0, -4) ?>"/>
	</div>
	<div class="info">
		<div class="description"><?= ucwords($description) ?></div>
		<div class="temp"><span><?= _('Temperature') ?></span> <?= $temperature ?> °C</div>
		<div class="pressure"><span><?= _('Pressure') ?></span> <?= $pressure ?> inHG</div>
		<div class="humidity"><span><?= _('Humidity') ?></span> <?= $humidity ?>%</div>
		<div class="wind"><span><?= _('Wind') ?></span> <?= $wind_direction ?> <?= $wind_speed ?> km/h</div>
	</div>
</div>