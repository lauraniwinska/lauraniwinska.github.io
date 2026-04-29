document.getElementById("Check").addEventListener("click", handleClick);

async function handleClick() {
    const city = document.getElementById("search").value.trim();
    if (!city) return;

    document.getElementById("box1").style.display = "flex";
    document.getElementById("box2").style.display = "flex";

    CurrentWeather(city);
    Forecast(city);
};


//aktualna pogoda - (XMLHttpRequest)
function CurrentWeather(city) {
    const xhr = new XMLHttpRequest();
    const url = `https://api.openweathermap.org/data/2.5/weather?q=${encodeURIComponent(city)}&appid=21ea904cf54668db3903f2c804f2271b&units=metric&lang=pl`;
    // ^
    // | encodeURIComponent jest po to aby móc obsługiwać np nazwy z polskimi znakami albo spacje itp
    xhr.open("GET", url, true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            const data = JSON.parse(xhr.responseText);
            //console.log(data);
            showCurrentWeather(data);
        } else {
            alert("Nie znaleziono miasta!");
        }
    };
    xhr.onerror = () => reject(new Error("Błąd sieci"));
    xhr.send();

}

function showCurrentWeather(data) {
    const now = new Date();

    document.getElementById("dataDZIS").innerHTML = `
        <p><strong>DATA:</strong> ${now.toLocaleDateString()}</p>
        <p><strong>GODZINA:</strong> ${now.toLocaleTimeString()}</p>
    `;

    document.getElementById("pogodaDZIS").innerHTML = `
        <p><strong>Temperatura:</strong> ${data.main.temp} °C | </p>
        <p><strong> Odczuwalna:</strong> ${data.main.feels_like} °C | </p>
        <p><strong> Pogoda:</strong> ${data.weather[0].description}</p>
        <img src="https://openweathermap.org/img/wn/${data.weather[0].icon}@2x.png">
    `;
}


//pogoda 5 dni (Fetch API)
async function Forecast(city) {
    const url = `https://api.openweathermap.org/data/2.5/forecast?q=${encodeURIComponent(city)}&appid=21ea904cf54668db3903f2c804f2271b&units=metric&lang=pl`;

    const response = await fetch(url);
    if (!response.ok) throw new Error("Błąd prognozy");
    const data = await response.json();
    //console.log(data);
    showForecast(data);
}

function showForecast(data) {
    const forecastBox = document.getElementById("pogodaPIECDNI");
    forecastBox.innerHTML = "";

    // grupujemy dane po datach
    const forecastByDate = {};
    let dayCount = 0;

    for (let i = 0; i < data.list.length && dayCount < 5; i++) {
        const pogoda = data.list[i];
        const date = new Date(pogoda.dt * 1000);
        const dateKey = date.toLocaleDateString();

        if (!forecastByDate[dateKey]) {
            forecastByDate[dateKey] = [];
            dayCount++;
        }
        forecastByDate[dateKey].push(pogoda);
    }

    for (const [dateStr, forecasts] of Object.entries(forecastByDate)) {
        forecastBox.innerHTML += `
            <div style="  border-top: solid 1px white;   border-left: solid 1px white; box-shadow: 1px 1px #517788; padding: 10px; background-color: #b0d4e3;  text-align: center; margin-bottom: 10px; margin-top: 10px;">
                <p style="margin: 0; font-weight: bold; font-size: 16px; font-family: 'Lucida Console', 'Courier New', monospace;">${dateStr}</p>
            </div>
        `;

        for (const pogoda of forecasts) {
            const time = new Date(pogoda.dt * 1000);

            forecastBox.innerHTML += `
                <div style="margin-bottom:10px; padding:10px; border:2px solid #000000; display: flex; gap: 10px; align-items: center;">
                    <div style="flex: 0 0 50px; text-align: center;">
                        <img src="https://openweathermap.org/img/wn/${pogoda.weather[0].icon}.png" style="width: 40px; margin: 0;">
                    </div>
                    <div style="flex: 1;">
                        <p style="margin: 2px 0; font-weight: bold; font-size: 18px; font-family: 'Lucida Console', 'Courier New', monospace;">${time.toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit'
            })}</p>
                        <p style="margin: 2px 0; font-family: 'Lucida Console', 'Courier New', monospace;"><strong>Temp:</strong> ${pogoda.main.temp} °C</p>
                        <p style="margin: 2px 0; font-family: 'Lucida Console', 'Courier New', monospace;"><strong>Odczuwalna:</strong> ${pogoda.main.feels_like} °C</p>
                        <p style="margin: 2px 0; font-family: 'Lucida Console', 'Courier New', monospace;"><strong> Pogoda:</strong> ${pogoda.weather[0].description}</p>
                    </div>
                </div>
            `;
        }
    }
}