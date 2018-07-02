const listRegions = document.getElementById('list_regions');

for (let region in regions) {
    const option = document.createElement('option');
    option.value = region;
    option.innerText = region;
    document.getElementById('list_regions').appendChild(option);
}

listRegions.addEventListener('change', function() {

    for (let region in regions) {
        if (region === listRegions.value) {
            document.getElementById('description_region').innerHTML = '';

            const titreH3 = document.createElement('h3');
            titreH3.textContent = regions[region].name;

            const regionDescription = document.createElement('p');
            regionDescription.classList.add('panel-item__summary');
            regionDescription.textContent = regions[region].description;

            document.getElementById('description_region').appendChild(titreH3);
            document.getElementById('description_region').appendChild(regionDescription);
        }
    }
});