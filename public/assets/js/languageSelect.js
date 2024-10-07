document.addEventListener("DOMContentLoaded", () => {
  // Verifica si estás en la página de Términos y Condiciones
  const isTermsPage =
    window.location.pathname === "/public/terminos-y-condiciones.php";

  if (!isTermsPage) return; // Sal del script si no estás en la página correcta

  const languageSelector = document.getElementById("language-selector");

  const elementsToTranslate = {
    title: document.getElementById("terms-conditions-title"),
    intro: document.getElementById("terms-conditions-intro"),
    sections: {
      section1: document.getElementById("terms-conditions-section-1"),
      section2: document.getElementById("terms-conditions-section-2"),
      section3: document.getElementById("terms-conditions-section-3"),
      section4: document.getElementById("terms-conditions-section-4"),
      section5: document.getElementById("terms-conditions-section-5"),
      section6: document.getElementById("terms-conditions-section-6"),
      section7: document.getElementById("terms-conditions-section-7"),
      section8: document.getElementById("terms-conditions-section-8"),
      section9: document.getElementById("terms-conditions-section-9"),
    },
    paragraphs: {
      p1: document.getElementById("terms-conditions-paragraph-1"),
      p2: document.getElementById("terms-conditions-paragraph-2"),
      p3: document.getElementById("terms-conditions-paragraph-3"),
      p4: document.getElementById("terms-conditions-paragraph-4"),
      p5: document.getElementById("terms-conditions-paragraph-5"),
      p6: document.getElementById("terms-conditions-paragraph-6"),
      p7: document.getElementById("terms-conditions-paragraph-7"),
      p8: document.getElementById("terms-conditions-paragraph-8"),
      p9: document.getElementById("terms-conditions-paragraph-9"),
    },
  };

  const loadTranslations = async (lang) => {
    if (lang === "es") {
      // Si el idioma es español, retorna null
      return null;
    }

    const currentTime = Date.now();
    const storedTranslations = localStorage.getItem(
      `translationsTermsConditions_${lang}`
    );
    const translationData = storedTranslations
      ? JSON.parse(storedTranslations)
      : null;

    // Verifica si las traducciones están almacenadas y si no ha pasado más de una hora
    if (translationData && currentTime - translationData.timestamp < 3600000) {
      return translationData.translations; // Retorna las traducciones almacenadas
    }

    // Si no hay traducciones almacenadas o ha pasado más de una hora, carga del servidor
    try {
      const response = await fetch(
        "/public/translations/terminos-y-condiciones.json"
      );
      if (!response.ok) throw new Error("Error al cargar traducciones");

      const data = await response.json();
      const langTranslations = data[lang];

      // Llama a la función para eliminar traducciones obsoletas
      removeOldTranslations(lang, langTranslations.version);

      // Actualiza el localStorage con nuevas traducciones y timestamp
      const translationsToStore = {
        version: langTranslations.version,
        translations: langTranslations,
        timestamp: currentTime, // Almacena el timestamp actual
      };
      localStorage.setItem(
        `translationsTermsConditions_${lang}`,
        JSON.stringify(translationsToStore)
      );

      return langTranslations;
    } catch (error) {
      console.error(error);
      // Opcional: Maneja el error, quizás cargando traducciones por defecto
    }
  };

  const removeOldTranslations = (lang, newVersion) => {
    const storedTranslations = localStorage.getItem(
      `translationsTermsConditions_${lang}`
    );
    if (!storedTranslations) return;

    const parsedStoredTranslations = JSON.parse(storedTranslations);
    if (parsedStoredTranslations.version !== newVersion) {
      // Elimina traducciones obsoletas
      localStorage.removeItem(`translationsTermsConditions_${lang}`);
    }
  };

  const updateText = (translations) => {
    elementsToTranslate.title.textContent = translations.terms_conditions_title;
    elementsToTranslate.intro.textContent = translations.terms_conditions_intro;

    elementsToTranslate.sections.section1.textContent =
      translations.terms_conditions_section_1;
    elementsToTranslate.sections.section2.textContent =
      translations.terms_conditions_section_2;
    elementsToTranslate.sections.section3.textContent =
      translations.terms_conditions_section_3;
    elementsToTranslate.sections.section4.textContent =
      translations.terms_conditions_section_4;
    elementsToTranslate.sections.section5.textContent =
      translations.terms_conditions_section_5;
    elementsToTranslate.sections.section6.textContent =
      translations.terms_conditions_section_6;
    elementsToTranslate.sections.section7.textContent =
      translations.terms_conditions_section_7;
    elementsToTranslate.sections.section8.textContent =
      translations.terms_conditions_section_8;
    elementsToTranslate.sections.section9.textContent =
      translations.terms_conditions_section_9;

    elementsToTranslate.paragraphs.p1.textContent =
      translations.terms_conditions_paragraph_1;
    elementsToTranslate.paragraphs.p2.textContent =
      translations.terms_conditions_paragraph_2;
    elementsToTranslate.paragraphs.p3.textContent =
      translations.terms_conditions_paragraph_3;
    elementsToTranslate.paragraphs.p4.textContent =
      translations.terms_conditions_paragraph_4;
    elementsToTranslate.paragraphs.p5.textContent =
      translations.terms_conditions_paragraph_5;
    elementsToTranslate.paragraphs.p6.textContent =
      translations.terms_conditions_paragraph_6;
    elementsToTranslate.paragraphs.p7.textContent =
      translations.terms_conditions_paragraph_7;
    elementsToTranslate.paragraphs.p8.textContent =
      translations.terms_conditions_paragraph_8;
    elementsToTranslate.paragraphs.p9.textContent =
      translations.terms_conditions_paragraph_9;
  };

  const setLanguage = (lang) => {
    languageSelector.value = lang;
    loadTranslations(lang).then((translations) => {
      if (translations) {
        updateText(translations);
      }
    });
    localStorage.setItem("selectedLanguage", lang);
  };

  languageSelector.addEventListener("change", (event) => {
    const selectedLanguage = event.target.value;
    if (selectedLanguage === "es") {
      // Recarga la página si se selecciona español
      window.location.reload();
    } else {
      setLanguage(selectedLanguage);
    }
  });

  const savedLanguage = localStorage.getItem("selectedLanguage") || "es";
  setLanguage(savedLanguage);
});

document.addEventListener("DOMContentLoaded", () => {
  const isTermsPage =
    window.location.pathname ===
    "/public/politicas-de-eliminacion-de-cuenta.php";

  if (!isTermsPage) return; // Sal del script si no estás en la página correcta

  const languageSelector = document.getElementById("language-selector");

  const elementsToTranslate = {
    title: document.getElementById("deletion-policy-title"),
    sections: {
      section1: document.getElementById("deletion-section-1"),
      section2: document.getElementById("deletion-section-2"),
      section3: document.getElementById("deletion-section-3"),
      section4: document.getElementById("deletion-section-4"),
      section5: document.getElementById("deletion-section-5"),
    },
    paragraphs: {
      p1: document.getElementById("deletion-paragraph-1"),
      p2: document.getElementById("deletion-paragraph-2"),
      p3: document.getElementById("deletion-paragraph-3"),
      p4: document.getElementById("deletion-paragraph-4"),
      p5: document.getElementById("deletion-paragraph-5"),
    },
    listItems: {
      li1: document.getElementById("deletion-list-item-1"),
      li2: document.getElementById("deletion-list-item-2"),
      li3: document.getElementById("deletion-list-item-3"),
      li4: document.getElementById("deletion-list-item-4"),
      li5: document.getElementById("deletion-list-item-5"),
      li6: document.getElementById("deletion-list-item-6"),
      li7: document.getElementById("deletion-list-item-7"),
      li8: document.getElementById("deletion-list-item-8"),
      li9: document.getElementById("deletion-list-item-9"),
      li10: document.getElementById("deletion-list-item-10"),
      li11: document.getElementById("deletion-list-item-11"),
    },
    subListItems: {
      sub1: document.getElementById("deletion-sublist-item-1"),
      sub2: document.getElementById("deletion-sublist-item-2"),
      sub3: document.getElementById("deletion-sublist-item-3"),
      sub4: document.getElementById("deletion-sublist-item-4"),
      sub5: document.getElementById("deletion-sublist-item-5"),
      sub6: document.getElementById("deletion-sublist-item-6"),
    },
    steps: {
      step1: document.getElementById("deletion-step-1"),
      step2: document.getElementById("deletion-step-2"),
      step3: document.getElementById("deletion-step-3"),
      step4: document.getElementById("deletion-step-4"),
    },
  };
  const loadTranslations = async (lang) => {
    if (lang === "es") {
      // Si el idioma es español, retorna null
      return null;
    }

    const currentTime = Date.now();
    const storedTranslations = localStorage.getItem(
      `translationsDeletionPolicy_${lang}`
    );
    const translationData = storedTranslations
      ? JSON.parse(storedTranslations)
      : null;

    // Verifica si las traducciones están almacenadas y si no ha pasado más de una hora
    if (translationData && currentTime - translationData.timestamp < 3600000) {
      // 1 hora en milisegundos
      return translationData.translations; // Retorna las traducciones almacenadas
    }

    // Si no hay traducciones almacenadas o ha pasado más de una hora, carga del servidor
    try {
      const response = await fetch(
        "/public/translations/politicas-de-eliminacion-de-cuenta.json"
      );
      if (!response.ok) throw new Error("Error al cargar traducciones");

      const data = await response.json();
      const langTranslations = data[lang];

      // Llama a la función para eliminar traducciones obsoletas
      removeOldTranslations(lang, langTranslations.version);

      // Actualiza el localStorage con nuevas traducciones y timestamp
      const translationsToStore = {
        version: langTranslations.version,
        translations: langTranslations,
        timestamp: currentTime, // Almacena el timestamp actual
      };
      localStorage.setItem(
        `translationsDeletionPolicy_${lang}`,
        JSON.stringify(translationsToStore)
      );

      return langTranslations;
    } catch (error) {
      console.error(error);
      // Opcional: Maneja el error, quizás cargando traducciones por defecto
    }
  };

  const removeOldTranslations = (lang, newVersion) => {
    const storedTranslations = localStorage.getItem(
      `translationsDeletionPolicy_${lang}`
    );
    if (!storedTranslations) return;

    const parsedStoredTranslations = JSON.parse(storedTranslations);
    if (parsedStoredTranslations.version !== newVersion) {
      // Elimina traducciones obsoletas
      localStorage.removeItem(`translationsDeletionPolicy_${lang}`);
    }
  };

  const updateText = (translations) => {
    elementsToTranslate.title.textContent = translations.deletion_policy_title;

    elementsToTranslate.sections.section1.textContent =
      translations.deletion_section_1;
    elementsToTranslate.sections.section2.textContent =
      translations.deletion_section_2;
    elementsToTranslate.sections.section3.textContent =
      translations.deletion_section_3;
    elementsToTranslate.sections.section4.textContent =
      translations.deletion_section_4;
    elementsToTranslate.sections.section5.textContent =
      translations.deletion_section_5;

    elementsToTranslate.paragraphs.p1.textContent =
      translations.deletion_paragraph_1;
    elementsToTranslate.paragraphs.p2.textContent =
      translations.deletion_paragraph_2;

    // Usamos innerHTML para mantener formato
    elementsToTranslate.paragraphs.p3.innerHTML =
      translations.deletion_paragraph_3;

    elementsToTranslate.paragraphs.p4.textContent =
      translations.deletion_paragraph_4;
    elementsToTranslate.paragraphs.p5.textContent =
      translations.deletion_paragraph_5;

    elementsToTranslate.listItems.li1.textContent =
      translations.deletion_list_item_1;
    elementsToTranslate.listItems.li2.textContent =
      translations.deletion_list_item_2;
    elementsToTranslate.listItems.li3.textContent =
      translations.deletion_list_item_3;
    elementsToTranslate.listItems.li4.textContent =
      translations.deletion_list_item_4;

    // Usamos innerHTML para los ítems con formato en negrita
    elementsToTranslate.listItems.li5.innerHTML =
      translations.deletion_list_item_5;
    elementsToTranslate.listItems.li6.innerHTML =
      translations.deletion_list_item_6;
    elementsToTranslate.listItems.li7.innerHTML =
      translations.deletion_list_item_7;
    elementsToTranslate.listItems.li8.innerHTML =
      translations.deletion_list_item_8;

    elementsToTranslate.listItems.li9.textContent =
      translations.deletion_list_item_9;
    elementsToTranslate.listItems.li10.textContent =
      translations.deletion_list_item_10;
    elementsToTranslate.listItems.li11.textContent =
      translations.deletion_list_item_11;

    elementsToTranslate.subListItems.sub1.textContent =
      translations.deletion_sublist_item_1;
    elementsToTranslate.subListItems.sub2.textContent =
      translations.deletion_sublist_item_2;
    elementsToTranslate.subListItems.sub3.textContent =
      translations.deletion_sublist_item_3;
    elementsToTranslate.subListItems.sub4.textContent =
      translations.deletion_sublist_item_4;
    elementsToTranslate.subListItems.sub5.textContent =
      translations.deletion_sublist_item_5;
    elementsToTranslate.subListItems.sub6.textContent =
      translations.deletion_sublist_item_6;

    elementsToTranslate.steps.step1.textContent = translations.deletion_step_1;
    elementsToTranslate.steps.step2.textContent = translations.deletion_step_2;
    elementsToTranslate.steps.step3.textContent = translations.deletion_step_3;
    elementsToTranslate.steps.step4.textContent = translations.deletion_step_4;
  };
  const setLanguage = (lang) => {
    languageSelector.value = lang;
    loadTranslations(lang).then((translations) => {
      if (translations) {
        updateText(translations);
      }
    });
    localStorage.setItem("selectedLanguage", lang);
  };

  languageSelector.addEventListener("change", (event) => {
    const selectedLanguage = event.target.value;
    if (selectedLanguage === "es") {
      // Recarga la página si se selecciona español
      window.location.reload();
    } else {
      setLanguage(selectedLanguage);
    }
  });

  const savedLanguage = localStorage.getItem("selectedLanguage") || "es";
  setLanguage(savedLanguage);
});

document.addEventListener("DOMContentLoaded", () => {
  const isTermsPage =
    window.location.pathname === "/public/politicas-de-privacidad.php";

  if (!isTermsPage) return;

  const languageSelector = document.getElementById("language-selector");

  const elementsToTranslate = {
    title: document.getElementById("privacy-policy-title"),
    intro: document.getElementById("privacy-policy-intro"),
    sections: {
      info: document.getElementById("info-section"),
      use: document.getElementById("use-section"),
      cookies: document.getElementById("cookies-section"),
      sharing: document.getElementById("sharing-section"),
      security: document.getElementById("security-section"),
      rights: document.getElementById("rights-section"),
      changes: document.getElementById("changes-section"),
      contact: document.getElementById("contact-section"),
    },
    paragraphs: {
      p1: document.getElementById("paragraph-1"),
      p2: document.getElementById("paragraph-2"),
      p3: document.getElementById("paragraph-3"),
      p4: document.getElementById("paragraph-4"),
      p5: document.getElementById("paragraph-5"),
      p6: document.getElementById("paragraph-6"),
      p7: document.getElementById("paragraph-7"),
      changesParagraph: document.getElementById("changes-paragraph"),
      contactParagraph: document.getElementById("contact-paragraph"),
    },
  };

  const loadTranslations = async (lang) => {
    if (lang === "es") {
      // Si el idioma es español, retorna null
      return null;
    }

    const currentTime = Date.now();
    const storedTranslations = localStorage.getItem(
      `translationsPolicy_${lang}`
    );
    const translationData = storedTranslations
      ? JSON.parse(storedTranslations)
      : null;

    if (translationData && currentTime - translationData.timestamp < 3600000) {
      return translationData.translations; // Retorna las traducciones almacenadas
    }

    try {
      const response = await fetch(
        "/public/translations/politica-de-privacidad.json"
      );
      if (!response.ok) throw new Error("Error al cargar traducciones");

      const data = await response.json();
      const langTranslations = data[lang];

      removeOldTranslations(lang, langTranslations.version);

      const translationsToStore = {
        version: langTranslations.version,
        translations: langTranslations,
        timestamp: currentTime,
      };
      localStorage.setItem(
        `translationsPolicy_${lang}`,
        JSON.stringify(translationsToStore)
      );

      return langTranslations;
    } catch (error) {
      console.error(error);
    }
  };

  const removeOldTranslations = (lang, newVersion) => {
    const storedTranslations = localStorage.getItem(
      `translationsPolicy_${lang}`
    );
    if (!storedTranslations) return;

    const parsedStoredTranslations = JSON.parse(storedTranslations);
    if (parsedStoredTranslations.version !== newVersion) {
      localStorage.removeItem(`translationsPolicy_${lang}`);
    }
  };

  const updateText = (translations) => {
    elementsToTranslate.title.textContent = translations.privacy_policy_title;
    elementsToTranslate.intro.textContent = translations.privacy_policy_intro;

    elementsToTranslate.sections.info.textContent = translations.info_section;
    elementsToTranslate.sections.use.textContent = translations.use_section;
    elementsToTranslate.sections.cookies.textContent =
      translations.cookies_section;
    elementsToTranslate.sections.sharing.textContent =
      translations.sharing_section;
    elementsToTranslate.sections.security.textContent =
      translations.security_section;
    elementsToTranslate.sections.rights.textContent =
      translations.rights_section;
    elementsToTranslate.sections.changes.textContent =
      translations.changes_section;
    elementsToTranslate.sections.contact.textContent =
      translations.contact_section;

    elementsToTranslate.paragraphs.p1.textContent = translations.paragraph_1;
    elementsToTranslate.paragraphs.p2.textContent = translations.paragraph_2;
    elementsToTranslate.paragraphs.p3.textContent = translations.paragraph_3;
    elementsToTranslate.paragraphs.p4.textContent = translations.paragraph_4;
    elementsToTranslate.paragraphs.p5.textContent = translations.paragraph_5;
    elementsToTranslate.paragraphs.p6.textContent = translations.paragraph_6;
    elementsToTranslate.paragraphs.p7.textContent = translations.paragraph_7;
    elementsToTranslate.paragraphs.changesParagraph.textContent =
      translations.changes_paragraph;
    elementsToTranslate.paragraphs.contactParagraph.textContent =
      translations.contact_paragraph;
  };

  const setLanguage = (lang) => {
    languageSelector.value = lang;
    loadTranslations(lang).then((translations) => {
      if (translations) {
        updateText(translations);
      }
    });
    localStorage.setItem("selectedLanguage", lang);
  };

  languageSelector.addEventListener("change", (event) => {
    const selectedLanguage = event.target.value;
    if (selectedLanguage === "es") {
      // Recarga la página si se selecciona español
      window.location.reload();
    } else {
      setLanguage(selectedLanguage);
    }
  });

  const savedLanguage = localStorage.getItem("selectedLanguage") || "es";
  setLanguage(savedLanguage);
});

document.addEventListener("DOMContentLoaded", () => {
  const languageSelector = document.getElementById("language-selector");

  const elementsToTranslate = {
    logo: document.getElementById("nav-logo"),
    productos: document.getElementById("nav-productos"),
    productosDesktop: document.getElementById("nav-productos-desktop"),
    locales: document.getElementById("nav-locales"),
    ofertas: document.getElementById("nav-ofertas"),
    reservas: document.getElementById("nav-reservas"),
    contacto: document.getElementById("nav-contacto"),
    usuario: document.getElementById("nav-usuario"),
  };

  const loadTranslations = async (lang) => {
    if (lang === "es") {
      return null; // Si el idioma es español, retorna null
    }

    const currentTime = Date.now();
    const storedTranslations = localStorage.getItem(`translationsNav_${lang}`);
    const translationData = storedTranslations
      ? JSON.parse(storedTranslations)
      : null;

    if (translationData && currentTime - translationData.timestamp < 3600000) {
      return translationData.translations;
    }

    try {
      const response = await fetch("/public/translations/nav.json");
      if (!response.ok) throw new Error("Error al cargar traducciones");

      const data = await response.json();
      const langTranslations = data[lang];

      removeOldTranslations(lang, langTranslations.version);

      const translationsToStore = {
        version: langTranslations.version,
        translations: langTranslations,
        timestamp: currentTime,
      };
      localStorage.setItem(
        `translationsNav_${lang}`,
        JSON.stringify(translationsToStore)
      );

      return langTranslations;
    } catch (error) {
      console.error(error);
    }
  };

  const removeOldTranslations = (lang, newVersion) => {
    const storedTranslations = localStorage.getItem(`translationsNav_${lang}`);
    if (!storedTranslations) return;

    const parsedStoredTranslations = JSON.parse(storedTranslations);
    if (parsedStoredTranslations.version !== newVersion) {
      localStorage.removeItem(`translationsNav_${lang}`);
    }
  };

  const updateText = (translations) => {
    elementsToTranslate.logo.textContent = translations.logo;
    elementsToTranslate.productos.textContent = translations.productos;
    if (elementsToTranslate.productosDesktop) {
      elementsToTranslate.productosDesktop.textContent = translations.productos;
    }
    elementsToTranslate.locales.textContent = translations.locales;
    elementsToTranslate.ofertas.textContent = translations.ofertas;
    elementsToTranslate.reservas.textContent = translations.reservas;
    elementsToTranslate.contacto.textContent = translations.contacto;
    elementsToTranslate.usuario.alt = translations.usuario;
  };

  const setLanguage = (lang) => {
    languageSelector.value = lang;
    loadTranslations(lang).then((translations) => {
      if (translations) {
        updateText(translations);
      } else {
        // Si el idioma es español, restablecer los textos por defecto
        updateText({
          logo: "Café Sabrosos",
          productos: "Tienda",
          locales: "Locales",
          ofertas: "Ofertas",
          reservas: "Reservas",
          contacto: "Contacto",
          favoritos: "Favoritos",
          usuario: "Usuario",
        });
      }
    });
    localStorage.setItem("selectedLanguage", lang);
  };

  languageSelector.addEventListener("change", (event) => {
    const selectedLanguage = event.target.value;
    setLanguage(selectedLanguage);
  });

  const savedLanguage = localStorage.getItem("selectedLanguage") || "es";
  setLanguage(savedLanguage);
});



document.addEventListener("DOMContentLoaded", () => {
  const languageSelector = document.getElementById("language-selector");

  const elementsToTranslate = {
    title: document.getElementById("footer-title"),
    intro: document.getElementById("footer-intro"),
    quickLinks: document.getElementById("footer-quick-links"),
    contactTitle: document.getElementById("footer-contact-title"),
    legalTitle: document.getElementById("footer-legal-title"),
    rights: document.getElementById("footer-rights"),
    links: {
      locales: document.getElementById("footer-locales"),
      productos: document.getElementById("footer-productos"),
      ofertas: document.getElementById("footer-ofertas"),
      reservas: document.getElementById("footer-reservas"),
      contacto: document.getElementById("footer-contacto"),
    },
    contactInfo: {
      location: document.getElementById("footer-location"),
      phone: document.getElementById("footer-phone"),
      email: document.getElementById("footer-email"),
    },
    legalLinks: {
      terms: document.getElementById("footer-terms"),
      privacy: document.getElementById("footer-privacy"),
      dataRemoval: document.getElementById("footer-data-removal"),
    },
  };

  const loadFooterTranslations = async (lang) => {
    if (lang === "es") {
      return null;
    }

    const currentTime = Date.now();
    const storedTranslations = localStorage.getItem(`translationsFooter_${lang}`);
    const translationData = storedTranslations ? JSON.parse(storedTranslations) : null;

    if (translationData && currentTime - translationData.timestamp < 3600000) {
      return translationData.translations;
    }

    try {
      const response = await fetch("/public/translations/footer.json");
      if (!response.ok) throw new Error("Error al cargar traducciones");

      const data = await response.json();
      const langTranslations = data[lang];

      const translationsToStore = {
        version: langTranslations.version,
        translations: langTranslations,
        timestamp: currentTime,
      };
      localStorage.setItem(`translationsFooter_${lang}`, JSON.stringify(translationsToStore));

      return langTranslations;
    } catch (error) {
      console.error(error);
    }
  };

  const updateFooterText = (translations) => {
    elementsToTranslate.title.textContent = translations.footer_title;
    elementsToTranslate.intro.textContent = translations.footer_intro;
    elementsToTranslate.quickLinks.textContent = translations.footer_quick_links;
    elementsToTranslate.contactTitle.textContent = translations.footer_contact_title;
    elementsToTranslate.legalTitle.textContent = translations.footer_legal_title;
    elementsToTranslate.rights.textContent = translations.footer_rights;

    elementsToTranslate.links.locales.textContent = translations.footer_locales;
    elementsToTranslate.links.productos.textContent = translations.footer_productos;
    elementsToTranslate.links.ofertas.textContent = translations.footer_ofertas;
    elementsToTranslate.links.reservas.textContent = translations.footer_reservas;
    elementsToTranslate.links.contacto.textContent = translations.footer_contacto;

    elementsToTranslate.contactInfo.location.textContent = translations.footer_contact_location;
    elementsToTranslate.contactInfo.phone.textContent = translations.footer_contact_phone;
    elementsToTranslate.contactInfo.email.textContent = translations.footer_contact_email;

    elementsToTranslate.legalLinks.terms.textContent = translations.footer_terms;
    elementsToTranslate.legalLinks.privacy.textContent = translations.footer_privacy;
    elementsToTranslate.legalLinks.dataRemoval.textContent = translations.footer_data_removal;
  };

  languageSelector.addEventListener("change", (event) => {
    const selectedLanguage = event.target.value;
    if (selectedLanguage !== "es") {
      loadFooterTranslations(selectedLanguage).then(translations => {
        if (translations) {
          updateFooterText(translations);
        }
      });
    }
  });

  const savedLanguage = localStorage.getItem("selectedLanguage") || "es";
  loadFooterTranslations(savedLanguage).then(translations => {
    if (translations) {
      updateFooterText(translations);
    }
  });
});

document.addEventListener("DOMContentLoaded", () => {

  const isTermsPage =
  window.location.pathname ===
  "/public/carrito.php";

if (!isTermsPage) return; // Sal del script si no estás en la página correcta

  const languageSelector = document.getElementById("language-selector");

  const elementsToTranslate = {
    cartTitle: document.getElementById("cart-title"),
    backToStore: document.getElementById("back-to-store"),
    productHeader: document.getElementById("product-header"),
    priceHeader: document.getElementById("price-header"),
    quantityHeader: document.getElementById("quantity-header"),
    totalHeader: document.getElementById("total-header"),
    actionsHeader: document.getElementById("actions-header"),
    emptyCartText: document.getElementById("empty-cart-text"),
    subtotalText: document.getElementById("subtotal-text"),
    taxText: document.getElementById("tax-text"),
    totalText: document.getElementById("total-text"),
    checkoutButton: document.getElementById("checkout-button"),
  };

  const loadCartTranslations = async (lang) => {
    if (lang === "es") {
      return null; // Para español, no cargamos desde el servidor
    }

    const currentTime = Date.now();
    const storedTranslations = localStorage.getItem(`translationsCart_${lang}`);
    const translationData = storedTranslations ? JSON.parse(storedTranslations) : null;

    if (translationData && currentTime - translationData.timestamp < 3600000) {
      return translationData.translations;
    }

    try {
      const response = await fetch("/public/translations/carrito.json");
      if (!response.ok) throw new Error("Error al cargar traducciones");

      const data = await response.json();
      const langTranslations = data[lang];

      removeOldTranslations(lang, langTranslations.version);

      const translationsToStore = {
        version: langTranslations.version,
        translations: langTranslations,
        timestamp: currentTime,
      };
      localStorage.setItem(`translationsCart_${lang}`, JSON.stringify(translationsToStore));

      return langTranslations;
    } catch (error) {
      console.error(error);
    }
  };

  const removeOldTranslations = (lang, newVersion) => {
    const storedTranslations = localStorage.getItem(`translationsCart_${lang}`);
    if (!storedTranslations) return;

    const parsedStoredTranslations = JSON.parse(storedTranslations);
    if (parsedStoredTranslations.version !== newVersion) {
      localStorage.removeItem(`translationsCart_${lang}`);
    }
  };

  const updateCartText = (translations) => {
    elementsToTranslate.cartTitle.textContent = translations.cart_title;
    elementsToTranslate.backToStore.textContent = translations.back_to_store;
    elementsToTranslate.productHeader.textContent = translations.product_header;
    elementsToTranslate.priceHeader.textContent = translations.price_header;
    elementsToTranslate.quantityHeader.textContent = translations.quantity_header;
    elementsToTranslate.totalHeader.textContent = translations.total_header;
    elementsToTranslate.actionsHeader.textContent = translations.actions_header;
    elementsToTranslate.emptyCartText.textContent = translations.empty_cart_text;

    // Actualiza solo los textos sin afectar los valores
    elementsToTranslate.subtotalText.innerHTML = `${translations.subtotal_text} <span id="subtotal">$0.00</span>`;
    elementsToTranslate.taxText.innerHTML = `${translations.tax_text} <span id="tax">$0.00</span>`;
    elementsToTranslate.totalText.innerHTML = `${translations.total_header} <span id="total">$0.00</span>`;

    elementsToTranslate.checkoutButton.textContent = translations.checkout_button;
  };

  languageSelector.addEventListener("change", (event) => {
    const selectedLanguage = event.target.value;
    if (selectedLanguage === "es") {
      // Restablecer los textos al español
      updateCartText({
        cart_title: "Tu Carrito",
        back_to_store: "Volver a la Tienda",
        product_header: "Producto",
        price_header: "Precio",
        quantity_header: "Cantidad",
        total_header: "Total",
        actions_header: "Acciones",
        empty_cart_text: "Tu carrito está vacío. ¡Vuelve a la tienda y agrega productos!",
        subtotal_text: "Subtotal:",
        tax_text: "Impuesto (20%):",
        checkout_button: "Proceder con el pago"
      });
    } else {
      loadCartTranslations(selectedLanguage).then(translations => {
        if (translations) {
          updateCartText(translations);
        }
      });
    }
  });

  const savedLanguage = localStorage.getItem("selectedLanguage") || "es";
  loadCartTranslations(savedLanguage).then(translations => {
    if (translations) {
      updateCartText(translations);
    }
  });
});

