import pandas as pd

# Create the data for the Excel sheet
data = {
    "Nombre del Socio": ["Alexis Bentancor", "Eduardo Delgado", "Jorge Martinez", "José Sanchez", "Luciano Britos"],
    "Rol": [
        "Director General y Líder de Equipo",
        "Especialista en Front-End y Director de Administración y Finanzas",
        "Especialista en Back-End",
        "Especialista en Back-End",
        "Especialista en Front-End"
    ],
    "Responsabilidades": [
        "Gestión de proyectos, toma de decisiones estratégicas, relaciones con clientes, liderazgo del equipo.",
        "Desarrollo de interfaces de usuario, control de finanzas y contabilidad, coordinación de tareas administrativas.",
        "Desarrollo de la lógica del servidor, gestión de bases de datos, integración de sistemas.",
        "Mantenimiento de sistemas en el servidor, optimización de bases de datos, implementación de seguridad.",
        "Diseño y desarrollo de la interfaz de usuario, optimización de la experiencia interactiva del usuario."
    ]
}

# Create a DataFrame
df = pd.DataFrame(data)

# Save to Excel
file_path = '/mnt/data/Socios_y_Roles_Plantilla.xlsx'
df.to_excel(file_path, index=False)

file_path
