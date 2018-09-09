import DisplayMap from "./DisplayMap.js";
import Transport from "./Transport.js";


window.onload = () => {

    const buttonMap = document.getElementById('menu-map-world');

    buttonMap.addEventListener('click', () => {

        const displayMap = new DisplayMap();
        displayMap.createMap();

        document.getElementById('button-close').addEventListener('click', () => {
            document.getElementById('game-container').style.opacity = '1';
            $('#map-window').fadeOut('slow');
        });

        /*
        if (document.getElementById('map-window')) {

            const transport = new Transport();
            transport.initPoint();
        }
        */
    });
};
