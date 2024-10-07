document.addEventListener("DOMContentLoaded", function () {
    const ws = new WebSocket('ws://localhost:8080');
    const categoryDetails = document.getElementById("category-details");
    const spinner = document.createElement("div");
    spinner.className = "loader"; // Usar la clase del nuevo spinner
    spinner.style.display = "none"; // Inicialmente oculto
    categoryDetails.appendChild(spinner);

    let categoriesData = null;
    let productsData = {};
    const languageSelector = document.getElementById("language-selector");
    const cacheDuration = 1000 * 60 * 30; // 30 minutos en milisegundos

    // Borrar localStorage excepto el idioma seleccionado
    function clearLocalStorage() {
        const selectedLanguage = localStorage.getItem("selectedLanguage");
        localStorage.clear(); // Borra todo el localStorage
        if (selectedLanguage) {
            localStorage.setItem("selectedLanguage", selectedLanguage); // Restaurar el idioma seleccionado
        }
    }

    // Guardar el idioma seleccionado en localStorage
    languageSelector.addEventListener("change", function () {
        const selectedLang = this.value;
        localStorage.setItem("selectedLanguage", selectedLang);
        loadPageContent(selectedLang);
    });

    ws.onmessage = async (event) => {
        const data = JSON.parse(event.data);
        console.log("Mensaje recibido:", data);
    
        if (data.action === "nuevoProductoAgregado") {
            console.log("Recargando productos y categorías...");
            clearLocalStorage(); // Limpia el localStorage al recibir un nuevo producto
            const savedLang = languageSelector.value;
            await loadCategories(null, savedLang); // Cargar categorías por defecto
        } else if (data.action === "productoEliminado") {
            console.log("Producto eliminado, actualizando la vista...");
            clearLocalStorage(); // Opcional: limpiar localStorage si es necesario
            const savedLang = languageSelector.value;
            await loadCategories(null, savedLang); // Recargar categorías o productos
        } else if (data.action === "productoDesactivado") {
            console.log("Producto desactivado, actualizando la vista...");
            clearLocalStorage(); // Opcional: limpiar localStorage si es necesario
            const savedLang = languageSelector.value;
            await loadCategories(null, savedLang); // Recargar categorías o productos
        } else if (data.action === "productoReactivado") {
            console.log("Producto reactivado, actualizando la vista...");
            clearLocalStorage(); // Opcional: limpiar localStorage si es necesario
            const savedLang = languageSelector.value;
            await loadCategories(null, savedLang); // Recargar categorías o productos
        } else if (data.action === "categoriaActualizada") {
            console.log("Categoría actualizada, recargando la vista...");
            clearLocalStorage(); // Limpia el localStorage si es necesario
            const savedLang = languageSelector.value;
            await loadCategories(null, savedLang); // Recargar categorías
        } else if (data.action === "categoriaEliminada") {
            console.log("Categoría eliminada, actualizando la vista...");
            clearLocalStorage(); // Limpia el localStorage si es necesario
            const savedLang = languageSelector.value;
            await loadCategories(null, savedLang); // Recargar categorías
        }
    };

    // Cargar las categorías
    function loadCategories(selectedCategory, lang) {
        const sidebar = document.getElementById("sidebar");
        
        return new Promise((resolve, reject) => {
            const cachedCategories = localStorage.getItem(`categories_${lang}`);
            const categoriesTimestamp = localStorage.getItem(`categoriesTimestamp_${lang}`);
    
            if (cachedCategories && categoriesTimestamp && (Date.now() - categoriesTimestamp) < cacheDuration) {
                categoriesData = JSON.parse(cachedCategories);
                renderCategories(selectedCategory);
                resolve();
            } else {
                // Muestra el spinner exclusivo para el sidebar
                sidebar.innerHTML = `<div class="sidebar-loader"></div>`;
                
                fetch(`/src/db/getCategories.php?lang=${lang}`)
                    .then((response) => response.json())
                    .then((categories) => {
                        categoriesData = categories;
                        localStorage.setItem(`categories_${lang}`, JSON.stringify(categories));
                        localStorage.setItem(`categoriesTimestamp_${lang}`, Date.now());
                        renderCategories(selectedCategory);
                        resolve();
                    })
                    .catch((error) => {
                        console.error("Error:", error);
                        sidebar.innerHTML = "<p>Error al cargar categorías.</p>";
                        reject(error);
                    });
            }
        });
    }

    function renderCategories(selectedCategory) {
        const sidebar = document.getElementById("sidebar");

        // Si no hay categoría seleccionada, seleccionar la primera
        if (!selectedCategory && categoriesData.length > 0) {
            selectedCategory = categoriesData[0].nombre;
            localStorage.setItem(`selectedCategory_${languageSelector.value}`, JSON.stringify({ category: selectedCategory, idCategoria: categoriesData[0].idCategoria }));
        }

        const categoriesHtml = categoriesData.map((category) => `
            <div class="category-item${category.nombre === selectedCategory ? " selected" : ""}" 
                 data-category="${category.nombre}" 
                 data-category-id="${category.idCategoria}">
                <img src="${category.imagen}" alt="${category.nombre}">
                <p>${category.nombre}</p>
            </div>
        `).join('');

        sidebar.innerHTML = `
            <h2>Nuestras categorías</h2>
            ${categoriesHtml}
        `;

        addSidebarEvents();

        const categoryTitle = document.getElementById("category-title");
        if (categoryTitle) {
            categoryTitle.textContent = selectedCategory;
        }
    }

    // Cargar productos
    function loadProducts(category, idCategoria, lang) {
        const categoryDetails = document.getElementById("category-details");

        categoryDetails.innerHTML = `
            <h1 id="category-title">${category.replace(/-/g, " ")}</h1>
            <div class="main-content">
                <div class="sidebar" id="sidebar"></div>
                <div class="product-grid">
                    <div class="loader"></div>
                </div>
            </div>
        `;
        const loader = categoryDetails.querySelector(".loader");
        showSpinner(loader);

        loadCategories(category, lang).then(() => {
            loadProductsData(category, idCategoria, lang);
        });
    }

    function loadProductsData(category, idCategoria, lang) {
        const cachedProducts = localStorage.getItem(`products_${lang}_${idCategoria}`);
        const productsTimestamp = localStorage.getItem(`productsTimestamp_${lang}_${idCategoria}`);

        if (cachedProducts && productsTimestamp && (Date.now() - productsTimestamp) < cacheDuration) {
            const data = JSON.parse(cachedProducts);
            productsData[category] = data;
            renderProducts(data, category);
        } else {
            fetch(`/src/db/product.php?idCategoria=${idCategoria}&lang=${lang}`)
                .then((response) => response.json())
                .then((data) => {
                    productsData[category] = data;
                    localStorage.setItem(`products_${lang}_${idCategoria}`, JSON.stringify(data));
                    localStorage.setItem(`productsTimestamp_${lang}_${idCategoria}`, Date.now());
                    renderProducts(data, category);
                })
                .catch((error) => console.error("Error:", error));
        }
    }

    function renderProducts(data, category) {
        const productosHtml = data.map((producto) => `
            <div class="product-item">
                <a href="productos.php?id=${producto.idProducto}" class="image-link">
                    <div class="image-container">
                        <img src="${producto.imagen}" alt="${producto.nombre}">
                    </div>
                </a>
                <h3>${producto.nombre}</h3>
                <span class="precio">€${parseFloat(producto.precio).toFixed(2)}</span>
            </div>
        `).join('');

        const categoryDetails = document.getElementById("category-details");
        categoryDetails.querySelector('.product-grid').innerHTML = productosHtml;
    }

    function showSpinner(loader) {
        loader.style.display = "flex";
    }

    function addSidebarEvents() {
        const categories = document.querySelectorAll(".sidebar .category-item");
        categories.forEach((category) => {
            category.addEventListener("click", function () {
                const sidebarCategory = this.getAttribute("data-category");
                const sidebarIdCategoria = this.getAttribute("data-category-id");

                categories.forEach((item) => item.classList.remove("selected"));
                this.classList.add("selected");

                loadProducts(sidebarCategory, sidebarIdCategoria, languageSelector.value);
                localStorage.setItem(`selectedCategory_${languageSelector.value}`, JSON.stringify({ category: sidebarCategory, idCategoria: sidebarIdCategoria }));
            });
        });
    }

    function initializeMobileDropdown() {
        const mobileCategoryLinks = document.querySelectorAll("#mobile-category-dropdown a");

        mobileCategoryLinks.forEach((link) => {
            link.addEventListener("click", function (e) {
                e.preventDefault();
                const selectedCategory = this.getAttribute("data-category");
                const selectedCategoryId = this.getAttribute("data-category-id");

                loadProducts(selectedCategory, selectedCategoryId, languageSelector.value);
                loadCategories(selectedCategory, languageSelector.value);

                localStorage.setItem(`selectedCategory_${languageSelector.value}`, JSON.stringify({ category: selectedCategory, idCategoria: selectedCategoryId }));

                document.querySelector(".dropdown").classList.remove("active");
                document.querySelector("#mobile-category-dropdown").classList.remove("open");
            });
        });
    }

    function loadPageContent(selectedLang) {
        const savedCategory = JSON.parse(localStorage.getItem(`selectedCategory_${selectedLang}`)) || null;
        loadCategories(savedCategory ? savedCategory.category : null, selectedLang)
            .then(() => {
                const categoryToLoad = savedCategory || { category: categoriesData[0].nombre, idCategoria: categoriesData[0].idCategoria };
                loadProducts(categoryToLoad.category, categoryToLoad.idCategoria, selectedLang);
            });
    }

    function initializePage() {
        const savedLang = localStorage.getItem("selectedLanguage") || languageSelector.value;
        languageSelector.value = savedLang;

        loadPageContent(savedLang);
        initializeMobileDropdown();
    }

    initializePage();
});
