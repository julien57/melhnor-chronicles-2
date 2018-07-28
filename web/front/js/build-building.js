let descriptionBuilding = document.getElementById('building-description');
const listBuildings = document.getElementById('build_building_building');

listBuildings.addEventListener('change', function() {

    for (let building in buildings) {

        if (building === listBuildings.value) {
            console.log(building);
            console.log(listBuildings.value);
            descriptionBuilding.innerHTML = '';

            const regionDescription = document.createElement('p');
            descriptionBuilding.classList.add('panel-item__summary');
            //descriptionBuilding.classList.add('col-md-10');
            descriptionBuilding.textContent = buildings[building].description;

            descriptionBuilding.appendChild(regionDescription);
        }
    }
});