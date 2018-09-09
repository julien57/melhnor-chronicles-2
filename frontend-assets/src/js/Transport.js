const region1 = 'one';
const region2 = 'two';

export default class Transport {

    initPoint() {

        let point = document.createElement('img');
        point.src = 'front/img/transport_point.png';
        point.id = 'transport-point';
        point.style.width = '15px';
        point.style.height = '15px';
        point.style.zIndex = '995';
        point.style.position = 'absolute';
        point.style.top = '35px';
        point.style.left = '215px';
        point.classList.add(region1+'To'+region2);

        document.getElementById('map-window').appendChild(point);
    }
}
