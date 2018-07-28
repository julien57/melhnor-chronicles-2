const buttonMap = document.getElementById('menu-map-world');

buttonMap.addEventListener('click', () => {

    document.querySelector('body').style.width = '100%';
    document.querySelector('body').style.height = '100%';
    document.querySelector('html').style.width = '100%';
    document.querySelector('html').style.height = '100%';
    document.getElementById('game-container').style.opacity = '0.3';

    let divElt = document.createElement('div');
    divElt.style.zIndex = '994';
    divElt.style.width = '1300px';
    divElt.style.height = '850px';
    divElt.style.position = 'absolute';
    divElt.style.top = '50px';
    divElt.style.left = '50px';
    divElt.classList.add('row');

    let mapElt = document.createElement('img');
    mapElt.src = 'front/img/map-parchment.jpg';
    mapElt.style.width = '1000px';
    mapElt.style.height = '850px';
    mapElt.style.zIndex = '995';
    $(mapElt).slideDown('slow');

    let monsterElt = document.createElement('img');
    monsterElt.id = 'monster-face';
    monsterElt.src = 'front/img/monster-face.png';
    monsterElt.style.position = 'absolute';
    monsterElt.style.top = '35px';
    monsterElt.style.left = '732px';
    $(monsterElt).fadeIn('slow');

    let blackCloud = document.createElement('img');
    blackCloud.id = 'black-cloud';
    blackCloud.src = 'front/img/black-cloud.png';
    blackCloud.style.position = 'absolute';
    blackCloud.style.top = '710px';
    blackCloud.style.left = '732px';
    $(blackCloud).fadeIn('slow');

    let whirlwind = document.createElement('img');
    whirlwind.src = 'front/img/whirlwind.png';
    whirlwind.id = 'whirlwind';

    let infoElt = document.createElement('div');
    infoElt.style.position = 'absolute';
    infoElt.style.left = '1000px';
    infoElt.style.top = '0';
    infoElt.style.width = '400px';
    infoElt.style.height = '850px';
    infoElt.style.background = 'url("front/img/parchment.png") no-repeat';
    infoElt.style.zIndex = '997';
    infoElt.style.border = '10px solid ##753B08';
    $(infoElt).slideDown('slow');

    let buttonClose = document.createElement('img');
    buttonClose.src = 'front/img/detruire.png';
    buttonClose.id = 'button-close';
    infoElt.appendChild(buttonClose);

    let cloudMap = document.createElement('img');
    cloudMap.style.position = 'absolute';
    cloudMap.style.top = '0';
    cloudMap.src = 'front/img/cloud-map.png';
    cloudMap.id = 'cloud-map';

    divElt.appendChild(mapElt);
    divElt.appendChild(monsterElt);
    divElt.appendChild(blackCloud);
    divElt.appendChild(whirlwind);
    divElt.appendChild(infoElt);
    divElt.appendChild(cloudMap);
    document.querySelector('body').appendChild(divElt);

    buttonClose.addEventListener('click', () => {
        document.getElementById('game-container').style.opacity = '1';
        $(divElt).fadeOut('slow');
    });

});