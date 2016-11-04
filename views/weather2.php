<div class="weather">
	<div class="icon">
		<img src="<?= $this->getAssetsUrl() . '/img/' . $icon ?>" alt="<?= substr($icon, 0, -4) ?>"/>
	</div>
	<div class="info">
		<div class="temp"><?= $temperature ?> °C</div>
		<div class="pressure"><?= $pressure ?> inHG</div>
		<div class="humidity"><?= $humidity ?>%</div>
	</div>
</div>