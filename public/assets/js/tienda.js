document.addEventListener("DOMContentLoaded", function () {
    const categoryDetails = document.getElementById("category-details");
    let categoriesData = null;
    let productsData = {};

    function scrollToH1() {
        const h1Element = document.getElementById("category-title");
        if (h1Element) {
            const h1Rect = h1Element.getBoundingClientRect();
            const offset = window.innerHeight / 2 - h1Rect.height / 2;
            window.scrollTo({
                top: h1Rect.top + window.pageYOffset - offset,
                behavior: 'smooth'
            });
        }
    }

    function scrollToCategoryDetails() {
        setTimeout(() => {
            const targetElement = document.querySelector("#category-details");
            if (targetElement) {
                const targetRect = targetElement.getBoundingClientRect();
                const offset = window.innerHeight / 2 - targetRect.height / 2;
                window.scrollTo({
                    top: targetRect.top + window.pageYOffset - offset,
                    behavior: "smooth",
                });
            }
        }, 100);
    }

    function loadCategories(selectedCategory) {
        if (categoriesData) {
            renderCategories(selectedCategory);
        } else {
            fetch("/src/db/getCategories.php")
                .then((response) => response.json())
                .then((categories) => {
                    categoriesData = categories;
                    renderCategories(selectedCategory);
                })
                .catch((error) => console.error("Error:", error));
        }
    }

    function renderCategories(selectedCategory) {
        const sidebar = document.getElementById("sidebar");
        let categoriesHtml = "";

        categoriesData.forEach((category) => {
            const isSelected = category.nombre === selectedCategory ? " selected" : "";
            categoriesHtml += `
                <div class="category-item${isSelected}" data-category="${category.nombre}" data-category-id="${category.idCategoria}">
                    <img src="${category.imagen}" alt="${category.nombre}">
                    <p>${category.nombre}</p>
                </div>
            `;
        });

        sidebar.innerHTML = `
            <h2>Nuestras categorías</h2>
            ${categoriesHtml}
        `;

        addSidebarEvents();
    }

    function observeH1Rendering(callback) {
        const observer = new MutationObserver((mutationsList, observer) => {
            const h1Element = document.querySelector("h1");
            if (h1Element) {
                observer.disconnect(); // Deja de observar una vez que el h1 está renderizado
                callback(h1Element); // Ejecuta el callback (scrollToH1 en este caso)
            }
        });
    
        observer.observe(document.body, { childList: true, subtree: true });
    }


    function loadProducts(category, idCategoria) {
        if (productsData[category]) {
            renderProducts(productsData[category], category);
        } else {
            fetch(`/src/db/product.php?idCategoria=${idCategoria}`)
                .then((response) => response.json())
                .then((data) => {
                    productsData[category] = data;
                    renderProducts(data, category);
                })
                .catch((error) => console.error("Error:", error));
        }
    }

    function renderProducts(data, category) {
        let productosHtml = "";
        data.forEach((producto) => {
            const precio = parseFloat(producto.precio);
            productosHtml += `
                <div class="product-item">
                    <a href="productos.php?id=${producto.idProducto}" class="image-link">
                        <div class="image-container">
                            <img src="${producto.imagen}" alt="${producto.nombre}">
                        </div>
                    </a>
                    <h3>${producto.nombre}</h3>
                    <span class="precio">€${precio.toFixed(2)}</span>
                </div>
            `;
        });

        categoryDetails.innerHTML = `
           <h1 id="category-title">${category.replace(/-/g, " ")}</h1>
            <div class="main-content">
                <div class="sidebar" id="sidebar">
                    <!-- Categories will be loaded here -->
                </div>
                <div class="product-grid">
                    ${productosHtml}
                </div>
            </div>
        `;

        const productGrid = categoryDetails.querySelector(".product-grid");
        const productItems = productGrid.children;
        const itemsPerRow = 3;
        const totalRows = Math.ceil(productItems.length / itemsPerRow);

        if (totalRows > 2) {
            document.getElementById("sidebar").classList.add("sticky-sidebar");
        }

           
        observeH1Rendering(() => {
            scrollToH1();
        });
        loadCategories(category);
    }

    function addSidebarEvents() {
        const categories = document.querySelectorAll(".sidebar .category-item");

        categories.forEach((category) => {
            category.addEventListener("click", function () {
                const sidebarCategory = this.getAttribute("data-category");
                const sidebarIdCategoria = this.getAttribute("data-category-id");

                categories.forEach((item) => item.classList.remove("selected"));
                this.classList.add("selected");

                loadProducts(sidebarCategory, sidebarIdCategoria);

                localStorage.setItem("selectedCategory", JSON.stringify({ category: sidebarCategory, idCategoria: sidebarIdCategoria }));

                window.location.hash = "category-details";
                scrollToCategoryDetails();
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

                loadProducts(selectedCategory, selectedCategoryId);
                loadCategories(selectedCategory);

                localStorage.setItem("selectedCategory", JSON.stringify({ category: selectedCategory, idCategoria: selectedCategoryId }));

                scrollToH1();

                // Close the dropdown after selection (optional)
                document.querySelector(".dropdown").classList.remove("active");
                document.querySelector("#mobile-category-dropdown").classList.remove("open");
            });
        });
    }

    function initializePage() {
        const savedCategory = localStorage.getItem("selectedCategory");

        if (savedCategory) {
            const { category, idCategoria } = JSON.parse(savedCategory);
            loadProducts(category, idCategoria);
            loadCategories(category);
            window.location.hash = "category-details";
            scrollToCategoryDetails();
        } else {
            const defaultCategory = "Cafés Especiales";
            const defaultIdCategoria = "1";
            loadProducts(defaultCategory, defaultIdCategoria);
            loadCategories(defaultCategory);
            window.location.hash = "category-details";
            scrollToCategoryDetails();
        }

        initializeMobileDropdown();
    }

    initializePage();
});
