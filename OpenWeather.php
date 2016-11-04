<?php

//namespace trsh;

class OpenWeather extends \CWidget
{
    public $apiURL = 'http://api.openweathermap.org/data/2.5/weather?q=';
    public $apiKey = '198f13f0e3e5a1dbd517a209d802054b';
    public $apiQ = 'Ragusa,it';
    public $data;
    public $cssFile = 'openweather.css';
    public $jsFile = 'openweather.js';
    public $view = 'weather1';
    public $wind_direction = 1; // 1 Direction, 0 Coordinates

    private $_assetsUrl;
    private $_inHG = 33.8638866667;
 
    public function getAssetsUrl()
    {
        if ($this->_assetsUrl === null) {
            $this->_assetsUrl = Yii::app()->assetManager->publish(Yii::getPathOfAlias('ext.OpenWeather.assets'), false, 1, true);
        }
        return $this->_assetsUrl;
    }

    public function init()
    {
        parent::init();
        $this->getAssetsUrl();
        Yii::app()->getClientScript()->registerCssFile($this->_assetsUrl . '/css/' . $this->cssFile);
        //Yii::app()->getClientScript()->registerScriptFile($this->_assetsUrl . '/js/' . $this->jsFile);
        if (!is_dir(Yii::getPathOfAlias('application.runtime.OpenWeather'))) {
            mkdir(Yii::getPathOfAlias('application.runtime.OpenWeather'));
        }
        setlocale(LC_MESSAGES, Yii::app()->language . '_' . strtoupper(Yii::app()->language));
        bindtextdomain("openweather", Yii::getPathOfAlias('ext.OpenWeather.i18n'));
        textdomain("openweather");
        $date = new DateTime();
        if (!is_file(Yii::getPathOfAlias('application.runtime.OpenWeather') . 'openweather.json') || (time() - filemtime(Yii::getPathOfAlias('application.runtime.OpenWeather') . 'openweather.json')) > 3600) {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $this->apiURL . $this->apiQ . '&appid=' . $this->apiKey);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($curl);
            file_put_contents(Yii::getPathOfAlias('application.runtime.OpenWeather') . 'openweather.json', $data);
        } else {
            $data = file_get_contents(Yii::getPathOfAlias('application.runtime.OpenWeather') . 'openweather.json');
        }
        $this->data = json_decode($data);
    }

    private function getIcon($code, $night)
    {
        switch ($code) {
            case 200: //thunderstorm with light rain
            case 201: //thunderstorm with rain
            case 202: //thunderstorm with heavy rain
            case 210: //light thunderstorm
            case 211: //thunderstorm
            case 212: //heavy thunderstorm
            case 221: //ragged thunderstorm
            case 230: //thunderstorm with light drizzle
            case 231: //thunderstorm with drizzle
            case 232: //thunderstorm with heavy drizzle
                $iconname = 'weather-storm';
                break;
            case 300: //light intensity drizzle
            case 301: //drizzle
            case 302: //heavy intensity drizzle
            case 310: //light intensity drizzle rain
            case 311: //drizzle rain
            case 312: //heavy intensity drizzle rain
            case 313: //shower rain and drizzle
            case 314: //heavy shower rain and drizzle
            case 321: //shower drizzle
                $iconname = 'weather-showers';
                break;
            case 500: //light rain
            case 501: //moderate rain
                $iconname = 'weather-showers';
                break;
            case 502: //heavy intensity rain
            case 503: //very heavy rain
            case 504: //extreme rain
                $iconname = 'weather-showers-scattered';
                break;
            case 511: //freezing rain
                $iconname = 'weather-showers';
                break;
            case 520: //light intensity shower rain
            case 521: //shower rain
            case 522: //heavy intensity shower rain
            case 531: //ragged shower rain
                $iconname = 'weather-showers';
                break;
            case 600: //light snow
            case 601: //snow
            case 602: //heavy snow
            case 611: //sleet
            case 612: //shower sleet
            case 615: //light rain and snow
            case 616: //rain and snow
            case 620: //light shower snow
            case 621: //shower snow
            case 622: //heavy shower snow
                $iconname = 'weather-snow';
                break;
            case 701: //mist
            case 711: //smoke
            case 721: //haze
            case 741: //Fog
                $iconname = 'weather-fog';
                break;
            case 731: //Sand/Dust Whirls
            case 751: //sand
            case 761: //dust
            case 762: //VOLCANIC ASH
            case 771: //SQUALLS
            case 781: //TORNADO
                $iconname = 'weather-severe-alert';
                break;
            case 800: //sky is clear
                $iconname = 'weather-clear';
                break;
            case 801: //few clouds
            case 802: //scattered clouds
                $iconname = 'weather-few-clouds';
                break;
            case 803: //broken clouds
                $iconname = 'weather-overcast';
                break;
            case 804: //overcast clouds
                $iconname = 'weather-overcast';
                break;
        }

        if ($night && is_file(Yii::getPathOfAlias('ext.OpenWeather.assets.img') . '/' . $iconname . '-night.png')) {
            return $iconname . '-night.png';
        } else {
            return $iconname . '.png';
        }
    }

    private function getDescription($code)
    {
        switch ($code) {
            case 200: //thunderstorm with light rain
                return _('thunderstorm with light rain');
            case 201: //thunderstorm with rain
                return _('thunderstorm with rain');
            case 202: //thunderstorm with heavy rain
                return _('thunderstorm with heavy rain');
            case 210: //light thunderstorm
                return _('light thunderstorm');
            case 211: //thunderstorm
                return _('thunderstorm');
            case 212: //heavy thunderstorm
                return _('heavy thunderstorm');
            case 221: //ragged thunderstorm
                return _('ragged thunderstorm');
            case 230: //thunderstorm with light drizzle
                return _('thunderstorm with light drizzle');
            case 231: //thunderstorm with drizzle
                return _('thunderstorm with drizzle');
            case 232: //thunderstorm with heavy drizzle
                return _('thunderstorm with heavy drizzle');
            case 300: //light intensity drizzle
                return _('light intensity drizzle');
            case 301: //drizzle
                return _('drizzle');
            case 302: //heavy intensity drizzle
                return _('heavy intensity drizzle');
            case 310: //light intensity drizzle rain
                return _('light intensity drizzle rain');
            case 311: //drizzle rain
                return _('drizzle rain');
            case 312: //heavy intensity drizzle rain
                return _('heavy intensity drizzle rain');
            case 313: //shower rain and drizzle
                return _('shower rain and drizzle');
            case 314: //heavy shower rain and drizzle
                return _('heavy shower rain and drizzle');
            case 321: //shower drizzle
                return _('shower drizzle');
            case 500: //light rain
                return _('light rain');
            case 501: //moderate rain
                return _('moderate rain');
            case 502: //heavy intensity rain
                return _('heavy intensity rain');
            case 503: //very heavy rain
                return _('very heavy rain');
            case 504: //extreme rain
                return _('extreme rain');
            case 511: //freezing rain
                return _('freezing rain');
            case 520: //light intensity shower rain
                return _('light intensity shower rain');
            case 521: //shower rain
                return _('shower rain');
            case 522: //heavy intensity shower rain
                return _('heavy intensity shower rain');
            case 531: //ragged shower rain
                return _('ragged shower rain');
            case 600: //light snow
                return _('light snow');
            case 601: //snow
                return _('snow');
            case 602: //heavy snow
                return _('heavy snow');
            case 611: //sleet
                return _('sleet');
            case 612: //shower sleet
                return _('shower sleet');
            case 615: //light rain and snow
                return _('light rain and snow');
            case 616: //rain and snow
                return _('rain and snow');
            case 620: //light shower snow
                return _('light shower snow');
            case 621: //shower snow
                return _('shower snow');
            case 622: //heavy shower snow
                return _('heavy shower snow');
            case 701: //mist
                return _('mist');
            case 711: //smoke
                return _('smoke');
            case 721: //haze
                return _('haze');
            case 731: //Sand/Dust Whirls
                return _('Sand/Dust Whirls');
            case 741: //Fog
                return _('Fog');
            case 751: //sand
                return _('sand');
            case 761: //dust
                return _('dust');
            case 762: //VOLCANIC ASH
                return _('VOLCANIC ASH');
            case 771: //SQUALLS
                return _('SQUALLS');
            case 781: //TORNADO
                return _('TORNADO');
            case 800: //sky is clear
                return _('sky is clear');
            case 801: //few clouds
                return _('few clouds');
            case 802: //scattered clouds
                return _('scattered clouds');
            case 803: //broken clouds
                return _('broken clouds');
            case 804: //overcast clouds
                return _('overcast clouds');
            default:
                return _('Not available');
        }
    }

    private function getWindDirection($deg)
    {
        //$arrows = ["\u2193", "\u2199", "\u2190", "\u2196", "\u2191", "\u2197", "\u2192", "\u2198"];
        $arrows = ["↓", "↙", "←", "↖", "↑", "↗", "→", "↘"];
        $letters = [_('N'), _('NE'), _('E'), _('SE'), _('S'), _('SW'), _('W'), _('NW')];
        $idx = round($deg / 45);
        return ($this->wind_direction) ? $arrows[$idx] : $letters[$idx];
    }

    public function run()
    {
        $temperature = $this->data->main->temp -273.15; // Celsius
        $night = false;
        if ($this->data->dt < $this->data->sys->sunrise || $this->data->dt > $this->data->sys->sunset) {
            $night = true;
        }
        $iconame = $this->getIcon($this->data->weather[0]->id, $night);
        $description = $this->getDescription($this->data->weather[0]->id);
        $pressure = round($this->data->main->pressure/$this->_inHG, 2);
        $humidity = $this->data->main->humidity;
        $direction = '';
        if (isset($this->data->wind->deg)) {
            $direction = $this->getWindDirection($this->data->wind->deg);
        }
        $speed = $this->data->wind->speed;
        $this->render($this->view, [
            'temperature' => $temperature,
            'icon' => $iconame,
            'pressure' => $pressure,
            'humidity' => $humidity,
            'description' => $description,
            'wind_direction' => $direction,
            'wind_speed' => $speed
            ]);
    }
}
