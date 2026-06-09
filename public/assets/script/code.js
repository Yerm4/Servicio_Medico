
    document.body.style.opacity = 1

// Add symptoms logic
const btnAddSintoma = document.getElementById("btn-add-sintoma");
const sintomaInput = document.getElementById("sintoma-input");
const sintomasLista = document.getElementById("sintomas-lista");

if (btnAddSintoma && sintomaInput && sintomasLista) {
    btnAddSintoma.addEventListener("click", () => {
        const value = sintomaInput.value.trim();
        if (value === "") return;

        const li = document.createElement("li");
        li.className = "sintoma-item";
        
        const spanText = document.createElement("span");
        spanText.textContent = value;
        li.appendChild(spanText);

        const hiddenInput = document.createElement("input");
        hiddenInput.type = "hidden";
        hiddenInput.name = "sintomas[]";
        hiddenInput.value = value;
        li.appendChild(hiddenInput);

        const btnRemove = document.createElement("button");
        btnRemove.type = "button";
        btnRemove.textContent = "✖";
        btnRemove.className = "btn-remove-sintoma";
        btnRemove.addEventListener("click", () => {
            li.remove();
        });
        li.appendChild(btnRemove);

        sintomasLista.appendChild(li);
        sintomaInput.value = "";
    });

    sintomaInput.addEventListener("keydown", (e) => {
        if (e.key === "Enter") {
            e.preventDefault();
            btnAddSintoma.click();
        }
    });
}

// Autocomplete pathology search logic
const diagnosticoSearch = document.getElementById("diagnostico-search");
const sugerenciasBox = document.getElementById("diagnosticos-sugerencias");
const diagnosticosSeleccionados = document.getElementById("diagnosticos-seleccionados");

if (diagnosticoSearch && sugerenciasBox && diagnosticosSeleccionados) {
    let timeout = null;

    diagnosticoSearch.addEventListener("input", () => {
        clearTimeout(timeout);
        const query = diagnosticoSearch.value.trim();

        if (query.length < 2) {
            sugerenciasBox.innerHTML = "";
            sugerenciasBox.style.display = "none";
            return;
        }

        timeout = setTimeout(() => {
            fetch(`index.php?ruta=buscar_patologia&q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    sugerenciasBox.innerHTML = "";
                    if (data.length > 0) {
                        data.forEach(item => {
                            const div = document.createElement("div");
                            div.className = "sugerencia-item";
                            div.style.cursor = "pointer";
                            div.textContent = `${item.codigo_icd} - ${item.patologia}`;
                            
                            div.addEventListener("mousedown", () => {
                                addDiagnosticoSeleccionado(item.codigo_icd, item.patologia);
                                sugerenciasBox.innerHTML = "";
                                sugerenciasBox.style.display = "none";
                                diagnosticoSearch.value = "";
                            });
                            sugerenciasBox.appendChild(div);
                        });
                        sugerenciasBox.style.display = "block";
                    } else {
                        const div = document.createElement("div");
                        div.className = "sugerencia-item-empty";
                        div.textContent = "No se encontraron patologías";
                        sugerenciasBox.appendChild(div);
                        sugerenciasBox.style.display = "block";
                    }
                })
                .catch(err => {
                    console.error("Error fetching pathologies:", err);
                });
        }, 300);
    });

    document.addEventListener("click", (e) => {
        if (e.target !== diagnosticoSearch && e.target !== sugerenciasBox) {
            sugerenciasBox.style.display = "none";
        }
    });

    function addDiagnosticoSeleccionado(code, name) {
        const existingInput = diagnosticosSeleccionados.querySelector(`input[value="${code}"]`);
        if (existingInput) return;

        const li = document.createElement("li");
        li.className = "diagnostico-item";
        
        const spanText = document.createElement("span");
        spanText.textContent = `${code} - ${name}`;
        li.appendChild(spanText);

        const hiddenInput = document.createElement("input");
        hiddenInput.type = "hidden";
        hiddenInput.name = "diagnosticos[]";
        hiddenInput.value = code;
        li.appendChild(hiddenInput);

        const btnRemove = document.createElement("button");
        btnRemove.type = "button";
        btnRemove.textContent = "✖";
        btnRemove.className = "btn-remove-diagnostico";
        btnRemove.addEventListener("click", () => {
            li.remove();
        });
        li.appendChild(btnRemove);

        diagnosticosSeleccionados.appendChild(li);
    }
}

function addCondicionToSelectedList(listId, id, name) {
    const list = document.getElementById(listId);
    if (!list) return;
    const existingInput = list.querySelector(`input[value="${id}"]`);
    if (existingInput) return;

    const li = document.createElement("li");
    li.className = "condicion-item";
    li.style.display = "flex";
    li.style.justifyContent = "space-between";
    li.style.alignItems = "center";
    li.style.padding = "5px";
    li.style.borderBottom = "1px solid #eee";
    
    const spanText = document.createElement("span");
    spanText.textContent = name;
    li.appendChild(spanText);

    const hiddenInput = document.createElement("input");
    hiddenInput.type = "hidden";
    hiddenInput.name = "condiciones[]";
    hiddenInput.value = id;
    li.appendChild(hiddenInput);

    const btnRemove = document.createElement("button");
    btnRemove.type = "button";
    btnRemove.textContent = "✖";
    btnRemove.className = "btn-remove-condicion";
    btnRemove.style.cursor = "pointer";
    btnRemove.style.background = "none";
    btnRemove.style.border = "none";
    btnRemove.style.color = "red";
    btnRemove.addEventListener("click", () => li.remove());
    li.appendChild(btnRemove);

    list.appendChild(li);
}

function cargarCondicionesPaciente(cedula, listId, infoDivId = null) {
    const list = listId ? document.getElementById(listId) : null;
    if (list) list.innerHTML = "";

    const infoDiv = infoDivId ? document.getElementById(infoDivId) : null;
    if (infoDiv) {
        infoDiv.innerHTML = "Cargando condiciones crónicas...";
        infoDiv.style.display = "block";
    }

    if (!cedula) {
        if (infoDiv) infoDiv.style.display = "none";
        return;
    }

    fetch(`index.php?ruta=buscar_condiciones_paciente&cedula=${cedula}`)
        .then(res => res.json())
        .then(data => {
            if (data.length > 0) {
                const names = data.map(item => item.condicion).join(", ");
                if (infoDiv) {
                    infoDiv.innerHTML = `<strong>Condiciones Crónicas:</strong> ${names}`;
                    infoDiv.style.display = "block";
                }
                if (list) {
                    data.forEach(item => {
                        addCondicionToSelectedList(listId, item.id, item.condicion);
                    });
                }
            } else {
                if (infoDiv) {
                    infoDiv.innerHTML = `<strong>Condiciones Crónicas:</strong> Ninguna`;
                    infoDiv.style.display = "block";
                }
            }
        })
        .catch(err => {
            console.error("Error loading patient conditions:", err);
            if (infoDiv) infoDiv.style.display = "none";
        });
}

function setupCondicionesAutocomplete(inputId, suggestionsId, listId) {
    const searchInput = document.getElementById(inputId);
    const suggestionsBox = document.getElementById(suggestionsId);
    if (!searchInput || !suggestionsBox) return;

    let timeout = null;

    searchInput.addEventListener("input", () => {
        clearTimeout(timeout);
        const query = searchInput.value.trim();

        if (query.length < 2) {
            suggestionsBox.innerHTML = "";
            suggestionsBox.style.display = "none";
            return;
        }

        timeout = setTimeout(() => {
            fetch(`index.php?ruta=buscar_condicion&q=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    suggestionsBox.innerHTML = "";
                    if (data.length > 0) {
                        data.forEach(item => {
                            const div = document.createElement("div");
                            div.className = "sugerencia-item";
                            div.style.cursor = "pointer";
                            div.textContent = item.condicion;
                            div.addEventListener("mousedown", () => {
                                addCondicionToSelectedList(listId, item.id, item.condicion);
                                suggestionsBox.innerHTML = "";
                                suggestionsBox.style.display = "none";
                                searchInput.value = "";
                            });
                            suggestionsBox.appendChild(div);
                        });
                        suggestionsBox.style.display = "block";
                    } else {
                        const div = document.createElement("div");
                        div.className = "sugerencia-item-empty";
                        div.textContent = "No se encontraron condiciones";
                        suggestionsBox.appendChild(div);
                        suggestionsBox.style.display = "block";
                    }
                });
        }, 300);
    });

    document.addEventListener("click", (e) => {
        if (e.target !== searchInput && e.target !== suggestionsBox) {
            suggestionsBox.style.display = "none";
        }
    });
}

// Reusable patient autocomplete helper
function setupPatientAutocomplete(inputId, suggestionsId, hiddenId, onSelectCallback) {
    const searchInput = document.getElementById(inputId);
    const suggestionsBox = document.getElementById(suggestionsId);
    const hiddenInput = document.getElementById(hiddenId);

    if (!searchInput || !suggestionsBox || !hiddenInput) return;

    let timeout = null;
    let justSelected = false;

    const clearIfSelected = () => {
        if (justSelected) {
            justSelected = false;
            return;
        }
        if (hiddenInput.value !== "") {
            searchInput.value = "";
            hiddenInput.value = "";
            if (onSelectCallback) onSelectCallback(null);
        }
    };

    searchInput.addEventListener("focus", clearIfSelected);
    searchInput.addEventListener("click", clearIfSelected);

    searchInput.addEventListener("input", () => {
        hiddenInput.value = "";
        if (onSelectCallback) onSelectCallback(null);
        clearTimeout(timeout);
        const query = searchInput.value.trim();

        if (query.length < 2) {
            suggestionsBox.innerHTML = "";
            suggestionsBox.style.display = "none";
            return;
        }

        timeout = setTimeout(() => {
            fetch(`index.php?ruta=buscar_paciente&q=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    suggestionsBox.innerHTML = "";
                    if (data.length > 0) {
                        data.forEach(item => {
                            const div = document.createElement("div");
                            div.className = "sugerencia-item";
                            div.style.cursor = "pointer";
                            div.textContent = `${item.cedula} - ${item.nombre} ${item.apellido}`;
                            div.addEventListener("mousedown", () => {
                                justSelected = true;
                                hiddenInput.value = item.cedula;
                                searchInput.value = `${item.cedula} - ${item.nombre} ${item.apellido}`;
                                suggestionsBox.innerHTML = "";
                                suggestionsBox.style.display = "none";
                                if (onSelectCallback) onSelectCallback(item.cedula);
                            });
                            suggestionsBox.appendChild(div);
                        });
                        suggestionsBox.style.display = "block";
                    } else {
                        const div = document.createElement("div");
                        div.className = "sugerencia-item-empty";
                        div.textContent = "No se encontraron pacientes";
                        suggestionsBox.appendChild(div);
                        suggestionsBox.style.display = "block";
                    }
                });
        }, 300);
    });

    searchInput.addEventListener("blur", () => {
        setTimeout(() => {
            if (hiddenInput.value === "") {
                searchInput.value = "";
            }
        }, 200);
    });

    document.addEventListener("click", (e) => {
        if (e.target !== searchInput && e.target !== suggestionsBox) {
            suggestionsBox.style.display = "none";
        }
    });
}

// 1. Initialise patient search on Iniciar Consulta
setupPatientAutocomplete("paciente-search", "pacientes-sugerencias", "cedula_paciente", (cedula) => {
    const condSearch = document.getElementById("condicion-search");
    const motivo = document.getElementById("motivo_de_visita");
    const obs = document.getElementById("observaciones");
    const med = document.getElementById("medicamento_suministrado");
    const sintomaInput = document.getElementById("sintoma-input");
    const btnAddSintoma = document.getElementById("btn-add-sintoma");
    const diagSearch = document.getElementById("diagnostico-search");
    const submitBtn = document.getElementById("btn-registrar-consulta-submit");

    const inputsToLock = [condSearch, motivo, obs, med, sintomaInput, btnAddSintoma, diagSearch, submitBtn];
    
    if (cedula) {
        inputsToLock.forEach(input => {
            if (input) input.disabled = false;
        });
        cargarCondicionesPaciente(cedula, "condiciones-seleccionadas");
    } else {
        inputsToLock.forEach(input => {
            if (input) input.disabled = true;
        });
        
        const condList = document.getElementById("condiciones-seleccionadas");
        if (condList) condList.innerHTML = "";
        
        const sintomasLista = document.getElementById("sintomas-lista");
        if (sintomasLista) sintomasLista.innerHTML = "";
        
        const diagsLista = document.getElementById("diagnosticos-seleccionados");
        if (diagsLista) diagsLista.innerHTML = "";
        
        if (motivo) motivo.value = "";
        if (obs) obs.value = "";
        if (med) med.value = "";
        if (sintomaInput) sintomaInput.value = "";
        if (diagSearch) diagSearch.value = "";
    }
});
setupCondicionesAutocomplete("condicion-search", "condiciones-sugerencias", "condiciones-seleccionadas");

const registrarForm = document.querySelector("#modalRegistrarConsulta form");
if (registrarForm) {
    registrarForm.addEventListener("reset", () => {
        const condSearch = document.getElementById("condicion-search");
        const motivo = document.getElementById("motivo_de_visita");
        const obs = document.getElementById("observaciones");
        const med = document.getElementById("medicamento_suministrado");
        const sintomaInput = document.getElementById("sintoma-input");
        const btnAddSintoma = document.getElementById("btn-add-sintoma");
        const diagSearch = document.getElementById("diagnostico-search");
        const submitBtn = document.getElementById("btn-registrar-consulta-submit");

        const inputsToLock = [condSearch, motivo, obs, med, sintomaInput, btnAddSintoma, diagSearch, submitBtn];
        
        inputsToLock.forEach(input => {
            if (input) input.disabled = true;
        });
        
        const condList = document.getElementById("condiciones-seleccionadas");
        if (condList) condList.innerHTML = "";
        
        const sintomasLista = document.getElementById("sintomas-lista");
        if (sintomasLista) sintomasLista.innerHTML = "";
        
        const diagsLista = document.getElementById("diagnosticos-seleccionados");
        if (diagsLista) diagsLista.innerHTML = "";
    });
}

// 2. Initialise patient search on Buscar Consulta (History)
setupPatientAutocomplete("paciente-search-buscar", "pacientes-sugerencias-buscar", "cedula_paciente_buscar", (cedula) => {
    const listContainer = document.getElementById("consultas-lista-buscar");
    if (!listContainer) return;
    listContainer.innerHTML = "";

    cargarCondicionesPaciente(cedula, null, "paciente-condiciones-info-buscar");

    if (!cedula) return;

    fetch(`index.php?ruta=buscar_consultas_paciente&cedula=${cedula}`)
        .then(res => res.json())
        .then(data => {
            if (data.length === 0) {
                listContainer.textContent = "No hay consultas registradas para este paciente.";
                return;
            }

            data.forEach(c => {
                const card = document.createElement("div");
                card.className = "action-card";
                card.style.marginTop = "10px";
                card.style.padding = "10px";
                card.style.border = "1px solid #ddd";

                let html = `<strong>Fecha:</strong> ${c.fecha_consulta}<br>`;
                html += `<strong>Médico:</strong> ${c.medico_nombre} ${c.medico_apellido} (${c.id_medico})<br>`;
                html += `<strong>Motivo:</strong> ${c.motivo_de_visita}<br>`;
                html += `<strong>Observaciones:</strong> ${c.observaciones || 'Ninguna'}<br>`;
                html += `<strong>Medicamento Suministrado:</strong> ${c.medicamento_suministrado || 'Ninguno'}<br>`;

                if (c.sintomas && c.sintomas.length > 0) {
                    html += `<strong>Síntomas:</strong> ${c.sintomas.join(', ')}<br>`;
                }
                if (c.diagnosticos && c.diagnosticos.length > 0) {
                    html += `<strong>Diagnósticos:</strong> `;
                    let diagArr = c.diagnosticos.map(d => `${d.codigo_icd_diagnostico} (${d.patologia})`);
                    html += diagArr.join(', ') + '<br>';
                }
                card.innerHTML = html;
                listContainer.appendChild(card);
            });
        });
});

// 3. Initialise patient search on Actualizar Consulta (Edit)
setupPatientAutocomplete("paciente-search-actualizar", "pacientes-sugerencias-actualizar", "cedula_paciente_actualizar", (cedula) => {
    const listContainer = document.getElementById("consultas-lista-actualizar");
    const editForm = document.getElementById("formulario-edicion-consulta");
    if (!listContainer) return;
    listContainer.innerHTML = "";
    if (editForm) editForm.style.display = "none";

    cargarCondicionesPaciente(cedula, null, "paciente-condiciones-info-actualizar");

    if (!cedula) return;

    fetch(`index.php?ruta=buscar_consultas_paciente&cedula=${cedula}`)
        .then(res => res.json())
        .then(data => {
            if (data.length === 0) {
                listContainer.textContent = "No hay consultas registradas para este paciente.";
                return;
            }

            data.forEach(c => {
                const card = document.createElement("div");
                card.className = "action-card";
                card.style.marginTop = "10px";
                card.style.padding = "10px";
                card.style.border = "1px solid #ddd";

                let html = `<strong>Fecha:</strong> ${c.fecha_consulta}<br>`;
                html += `<strong>Motivo:</strong> ${c.motivo_de_visita}<br>`;
                
                const btnEdit = document.createElement("button");
                btnEdit.type = "button";
                btnEdit.className = "action-card__button";
                btnEdit.style.cursor = "pointer";
                btnEdit.style.marginTop = "8px";
                btnEdit.textContent = "Editar Consulta";
                
                btnEdit.addEventListener("click", () => {
                    loadConsultaIntoEditForm(c);
                });

                card.innerHTML = html;
                card.appendChild(btnEdit);
                listContainer.appendChild(card);
            });
        });
});

function loadConsultaIntoEditForm(c) {
    const editForm = document.getElementById("formulario-edicion-consulta");
    const editIdInput = document.getElementById("edit_id_consulta");
    const editMotivoInput = document.getElementById("edit_motivo_de_visita");
    const editObsInput = document.getElementById("edit_observaciones");
    const editMedInput = document.getElementById("edit_medicamento_suministrado");
    const editSintomasLista = document.getElementById("edit-sintomas-lista");
    const editDiagsLista = document.getElementById("edit-diagnosticos-seleccionados");
    const editCondsLista = document.getElementById("edit-condiciones-seleccionadas");

    if (!editForm) return;

    editIdInput.value = c.id;
    editMotivoInput.value = c.motivo_de_visita;
    editObsInput.value = c.observaciones;
    if (editMedInput) editMedInput.value = c.medicamento_suministrado || "";

    editSintomasLista.innerHTML = "";
    editDiagsLista.innerHTML = "";
    if (editCondsLista) editCondsLista.innerHTML = "";

    if (c.sintomas) {
        c.sintomas.forEach(s => {
            addSintomaToEditList(s);
        });
    }

    if (c.diagnosticos) {
        c.diagnosticos.forEach(d => {
            addDiagnosticoToEditList(d.codigo_icd_diagnostico, d.patologia);
        });
    }

    if (c.id_usuario) {
        cargarCondicionesPaciente(c.id_usuario, "edit-condiciones-seleccionadas");
    }

    editForm.style.display = "block";
}

function addSintomaToEditList(value) {
    const editSintomasLista = document.getElementById("edit-sintomas-lista");
    const li = document.createElement("li");
    li.className = "sintoma-item";
    
    const spanText = document.createElement("span");
    spanText.textContent = value;
    li.appendChild(spanText);

    const hiddenInput = document.createElement("input");
    hiddenInput.type = "hidden";
    hiddenInput.name = "sintomas[]";
    hiddenInput.value = value;
    li.appendChild(hiddenInput);

    const btnRemove = document.createElement("button");
    btnRemove.type = "button";
    btnRemove.textContent = "✖";
    btnRemove.className = "btn-remove-sintoma";
    btnRemove.style.cursor = "pointer";
    btnRemove.addEventListener("click", () => li.remove());
    li.appendChild(btnRemove);

    editSintomasLista.appendChild(li);
}

function addDiagnosticoToEditList(code, name) {
    const editDiagsLista = document.getElementById("edit-diagnosticos-seleccionados");
    const existingInput = editDiagsLista.querySelector(`input[value="${code}"]`);
    if (existingInput) return;

    const li = document.createElement("li");
    li.className = "diagnostico-item";
    
    const spanText = document.createElement("span");
    spanText.textContent = `${code} - ${name}`;
    li.appendChild(spanText);

    const hiddenInput = document.createElement("input");
    hiddenInput.type = "hidden";
    hiddenInput.name = "diagnosticos[]";
    hiddenInput.value = code;
    li.appendChild(hiddenInput);

    const btnRemove = document.createElement("button");
    btnRemove.type = "button";
    btnRemove.textContent = "✖";
    btnRemove.className = "btn-remove-diagnostico";
    btnRemove.style.cursor = "pointer";
    btnRemove.addEventListener("click", () => li.remove());
    li.appendChild(btnRemove);

    editDiagsLista.appendChild(li);
}

// 4. Edit form symptom handling
const btnEditAddSintoma = document.getElementById("btn-edit-add-sintoma");
const editSintomaInput = document.getElementById("edit-sintoma-input");
if (btnEditAddSintoma && editSintomaInput) {
    btnEditAddSintoma.addEventListener("click", () => {
        const val = editSintomaInput.value.trim();
        if (val !== "") {
            addSintomaToEditList(val);
            editSintomaInput.value = "";
        }
    });
    editSintomaInput.addEventListener("keydown", (e) => {
        if (e.key === "Enter") {
            e.preventDefault();
            btnEditAddSintoma.click();
        }
    });
}

// 5. Edit form diagnosis autocomplete
const editDiagSearch = document.getElementById("edit-diagnostico-search");
const editDiagSugerencias = document.getElementById("edit-diagnosticos-sugerencias");
if (editDiagSearch && editDiagSugerencias) {
    let editTimeout = null;
    editDiagSearch.addEventListener("input", () => {
        clearTimeout(editTimeout);
        const query = editDiagSearch.value.trim();
        if (query.length < 2) {
            editDiagSugerencias.innerHTML = "";
            editDiagSugerencias.style.display = "none";
            return;
        }
        editTimeout = setTimeout(() => {
            fetch(`index.php?ruta=buscar_patologia&q=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    editDiagSugerencias.innerHTML = "";
                    if (data.length > 0) {
                        data.forEach(item => {
                            const div = document.createElement("div");
                            div.className = "sugerencia-item";
                            div.style.cursor = "pointer";
                            div.textContent = `${item.codigo_icd} - ${item.patologia}`;
                            div.addEventListener("mousedown", () => {
                                addDiagnosticoToEditList(item.codigo_icd, item.patologia);
                                editDiagSugerencias.innerHTML = "";
                                editDiagSugerencias.style.display = "none";
                                editDiagSearch.value = "";
                            });
                            editDiagSugerencias.appendChild(div);
                        });
                        editDiagSugerencias.style.display = "block";
                    } else {
                        const div = document.createElement("div");
                        div.className = "sugerencia-item-empty";
                        div.textContent = "No se encontraron patologías";
                        editDiagSugerencias.appendChild(div);
                        editDiagSugerencias.style.display = "block";
                    }
                });
        }, 300);
    });
    document.addEventListener("click", (e) => {
        if (e.target !== editDiagSearch && e.target !== editDiagSugerencias) {
            editDiagSugerencias.style.display = "none";
        }
    });
}

setupCondicionesAutocomplete("edit-condicion-search", "edit-condiciones-sugerencias", "edit-condiciones-seleccionadas");