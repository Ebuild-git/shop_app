document.addEventListener('DOMContentLoaded', function () {
    const dropdown = document.querySelector('.custom-dropdown');
    const selected = dropdown.querySelector('.dropdown-selected');
    const options = dropdown.querySelectorAll('.dropdown-option');
    const hiddenSelect = document.querySelector('#filtre-ordre');
    const filters = document.querySelectorAll('input[name="ordre_prix"]');
    selected.addEventListener('click', function () {
        dropdown.classList.toggle('active');
    });
    options.forEach(option => {
        option.addEventListener('click', function () {
            const value = this.getAttribute('data-value');
            const text = this.textContent;
            selected.innerHTML = `${text} <i class="fas fa-chevron-down"></i>`;
            hiddenSelect.value = value;

            updateFilters(value);
            hiddenSelect.dispatchEvent(new Event('change'));
            dropdown.classList.remove('active');
        });
    });
    document.addEventListener('click', function (event) {
        if (!dropdown.contains(event.target)) {
            dropdown.classList.remove('active');
        }
    });
    function updateFilters(value) {
        filters.forEach(filter => {
            filter.checked = filter.value === value;
        });
    }
    filters.forEach(filter => {
        filter.addEventListener('change', function () {
            if (this.checked) {
                const correspondingOption = Array.from(options).find(option => option.getAttribute('data-value') === this.value);
                if (correspondingOption) {
                    const text = correspondingOption.textContent;
                    selected.innerHTML = `${text} <i class="fas fa-chevron-down"></i>`;
                    hiddenSelect.value = this.value;
                }
            }
        });
    });
});



document.addEventListener('DOMContentLoaded', function () {
    const trierParButton = document.getElementById('trier-par-mobile');
    const sortingOptions = document.getElementById('sorting-options-mobile');
    const filtreOrdreSelect = document.getElementById('filtre-ordre');
    const trierParText = trierParButton.querySelector('span');
    const filterConditionButton = document.getElementById('filter-condition-mobile');
    const conditionOptions = document.getElementById('condition-options-mobile');
    const dynamicFilterButton = document.getElementById('dynamic-filter-toggle');
    const dynamicFilterOptions = document.getElementById('dynamic-filter-mobile');

    function closeAllDropdowns() {
        sortingOptions.style.display = 'none';
        conditionOptions.style.display = 'none';
        if (dynamicFilterOptions) dynamicFilterOptions.style.display = 'none';
    }

    trierParButton.addEventListener('click', function (event) {
        event.stopPropagation();
        closeAllDropdowns();
        sortingOptions.style.display = (sortingOptions.style.display === 'none' || sortingOptions.style.display === '') ? 'block' : 'none';
    });

    filterConditionButton.addEventListener('click', function (event) {
        event.stopPropagation();
        closeAllDropdowns();
        conditionOptions.style.display = (conditionOptions.style.display === 'none' || conditionOptions.style.display === '') ? 'block' : 'none';
    });

    // If dynamic filter button exists
    if (dynamicFilterButton) {
        dynamicFilterButton.addEventListener('click', function (event) {
            event.stopPropagation();
            closeAllDropdowns();
            dynamicFilterOptions.style.display = (dynamicFilterOptions.style.display === 'none' || dynamicFilterOptions.style.display === '') ? 'block' : 'none';
        });
    }

    document.querySelectorAll('#sorting-options-mobile .dropdown-option').forEach(option => {
        option.addEventListener('click', function () {
            const value = this.getAttribute('data-value');
            const optionText = this.textContent;
            filtreOrdreSelect.value = value;
            filtreOrdreSelect.dispatchEvent(new Event('change'));
            trierParText.textContent = optionText;
            closeAllDropdowns();
        });
    });

    // Close dropdowns if clicked outside
    document.addEventListener('click', function (event) {
        if (!trierParButton.contains(event.target) && !sortingOptions.contains(event.target) &&
            !filterConditionButton.contains(event.target) && !conditionOptions.contains(event.target) &&
            (!dynamicFilterButton || (!dynamicFilterButton.contains(event.target) && !dynamicFilterOptions.contains(event.target)))) {
            closeAllDropdowns();
        }
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.querySelector('.search-sidebar');
    const sortingOptions = document.querySelectorAll('#sorting-options-mobile .dropdown-option');
    const filterOptions = document.querySelectorAll('#condition-options-mobile .dropdown-option');
    const dropdowns = document.querySelectorAll('.dropdown');

    function closeDropdown(dropdown) {
        dropdown.classList.remove('show');
    }

    sortingOptions.forEach(option => {
        option.addEventListener('click', function () {
            if (window.innerWidth <= 768) {
                sidebar.classList.toggle('hide-on-mobile', option.getAttribute('data-value') !== "");
                dropdowns.forEach(dropdown => closeDropdown(dropdown));
            }
        });
    });

    filterOptions.forEach(option => {
        option.addEventListener('click', function () {
            if (window.innerWidth <= 768) {
                sidebar.classList.toggle('hide-on-mobile', option.getAttribute('data-value') !== "");
                dropdowns.forEach(dropdown => closeDropdown(dropdown));
            }
        });
    });
});


document.addEventListener('DOMContentLoaded', function () {
    const toggles = document.querySelectorAll('.collapse-toggle');

    toggles.forEach(toggle => {
        toggle.addEventListener('click', function () {
            const targetId = this.getAttribute('data-target');
            const target = document.querySelector(targetId);
            const icon = this.querySelector('.collapse-icon i');

            if (target) {
                if (target.classList.contains('active')) {
                    target.classList.remove('active');
                    this.setAttribute('aria-expanded', 'false');
                    icon.classList.replace('fa-minus', 'fa-plus'); // Change to plus icon
                } else {
                    target.classList.add('active');
                    this.setAttribute('aria-expanded', 'true');
                    icon.classList.replace('fa-plus', 'fa-minus'); // Change to minus icon
                }
            }
        });
    });
});


document.addEventListener('DOMContentLoaded', function() {
    function toggleDropdown(dropdownId, triggerId) {
        var dropdown = document.getElementById(dropdownId);
        var trigger = document.getElementById(triggerId);
        var isDisplayed = window.getComputedStyle(dropdown).display !== 'none';
        dropdown.style.display = isDisplayed ? 'none' : 'block';
        trigger.classList.toggle('active');
    }
    document.getElementById('filter-price-mobile').addEventListener('click', function() {
        toggleDropdown('price-options-mobile', 'filter-price-mobile');
    });

    document.getElementById('filter-condition-category-mobile').addEventListener('click', function() {
        toggleDropdown('condition-options-category-mobile', 'filter-condition-category-mobile');
    });
    document.addEventListener('click', function(event) {
        if (!event.target.matches('.custom-filter-option, .custom-filter-option *')) {
            document.querySelectorAll('.custom-dropdown-container').forEach(function(dropdown) {
                dropdown.style.display = 'none';
                document.querySelectorAll('.custom-filter-option').forEach(function(trigger) {
                    trigger.classList.remove('active');
                });
            });
        }
    });
});
