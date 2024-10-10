var start_date = document.getElementById("event-start-date");
var timepicker1 = document.getElementById("timepicker1");
var timepicker2 = document.getElementById("timepicker2");
var date_range = null;
var T_check = null;

document.addEventListener("DOMContentLoaded", function () {
    //flatPickrInit();
    /*  var addEvent = new bootstrap.Modal(document.getElementById('event-modal'), {
         keyboard: false
     }); */
    document.getElementById('event-modal');
    var modalTitle = document.getElementById('modal-title');
    var formEvent = document.getElementById('form-event');
    var selectedEvent = null;
    var forms = document.getElementsByClassName('needs-validation');

    /* initialize the calendar */

    var Draggable = FullCalendar.Draggable;
    var externalEventContainerEl = document.getElementById('external-events');

    // Remover o array defaultEvents, pois iremos buscar os eventos do servidor
    // var defaultEvents = [ ... ]; // Removido

    // init draggable
    /* new Draggable(externalEventContainerEl, {
        itemSelector: '.external-event',
        eventData: function (eventEl) {
            return {
                id: Math.floor(Math.random() * 11000),
                title: eventEl.innerText,
                allDay: true,
                start: new Date(),
                className: eventEl.getAttribute('data-class')
            };
        }
    });
 */
    var calendarEl = document.getElementById('calendar');

    function addNewEvent(info) {
        document.getElementById('form-event').reset();
        document.getElementById('btn-delete-event').setAttribute('hidden', true);
        addEvent.show();
        formEvent.classList.remove("was-validated");
        formEvent.reset();
        selectedEvent = null;
        modalTitle.innerText = 'Adicionar Evento';
        newEventData = info;
        document.getElementById("edit-event-btn").setAttribute("data-id", "new-event");
        document.getElementById('edit-event-btn').click();
        document.getElementById("edit-event-btn").setAttribute("hidden", true);
    }

    var eventCategoryChoice = new Choices("#event-category", {
        searchEnabled: false
    });
    var calendar = new FullCalendar.Calendar(calendarEl, {
        timeZone: 'America/Sao_Paulo', // Define o fuso horário para São Paulo
        locale: 'pt-br', // Define o idioma para Português Brasileiro
        editable: true,
        droppable: true,
        selectable: true,
        navLinks: true,
        initialView: 'multiMonthYear',
        themeSystem: 'bootstrap',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'multiMonthYear,dayGridMonth,timeGridWeek,timeGridDay,listMonth'
        },
        buttonText: {
            today: 'Hoje',
            multiMonthYear: 'Ano',
            dayGridMonth: 'Mês',
            timeGridWeek: 'Semana',
            timeGridDay: 'Dia',
            listMonth: 'Lista Mensal'
        },
        buttonHints: {
            prev: 'Ano anterior',
            next: 'Próximo ano',
            today(buttonText, unit) {
                return (unit === 'day') ? 'Dia' : `Este ${buttonText}`;
            },
        },
        viewHint: 'Ver',
        navLinkHint: 'Ir para $0',
        moreLinkHint(eventCnt) {
            return `Há mais ${eventCnt} evento${eventCnt === 1 ? '' : 's'}`;
        },
        moreLinkText: 'mais',
        displayEventTime: false, // Ocultar o horário na exibição do evento
    
        // Personalização da Renderização dos Eventos
        eventContent: function(arg) {
            console.log(arg)
            var event = arg.event;
            var eventTitle = event.title;
            var eventDate = new Date(event.end);
            var formattedDate = eventDate.toLocaleDateString('pt-BR');
    
            // Verificar se o evento possui uma URL definida
            var eventUrl = arg.event.url; // Define '#' como fallback
    
            // Criar o elemento de link
            var linkEl = document.createElement('a');
            linkEl.href = eventUrl;
            linkEl.style.color = 'inherit'; // Herda a cor do evento
            linkEl.style.textDecoration = 'none'; // Remove o sublinhado
    
            // Criar o conteúdo HTML do evento
            var innerHtml = `
                <div class="fc-daygrid-event-harness">
                    <div class="fc-event-main">
                        <div class="fc-event-title">${eventTitle}</div>
                        <div class="fc-event-date">${formattedDate}</div>
                    </div>
                </div>
            `;
    
            // Inserir o conteúdo no link
            linkEl.innerHTML = innerHtml;
    
            return { domNodes: [linkEl] };
        },
    
        events: function (fetchInfo, successCallback, failureCallback) {
            // Fazer uma chamada AJAX para obter os eventos
            $.ajax({
                url: '/admin/events', // A rota que retorna os eventos em JSON
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    // Processar os eventos para definir 'start' igual a 'end'
                    var events = response.map(function(event) {
                        return {
                            id: event.id,
                            title: event.title,
                            start: event.end, // Define 'start' igual a 'end'
                            end: event.end,
                            url: event.url,
                            // Inclua outros campos necessários
                            extendedProps: event.extendedProps || {},
                            classNames: event.classNames || [],
                            allDay: true // Define como evento de dia inteiro, se aplicável
                        };
                    });
                    // Chamar o successCallback com os eventos processados
                    successCallback(events);
                    // Atualizar o upcomingEvent com os eventos carregados
                    upcomingEvent(events);
                },
                error: function (xhr) {
                    // Chamar o failureCallback se houver um erro
                    failureCallback(xhr);
                }
            });
        },
        eventResize: function (info) {
            // Atualizar o evento no servidor
            $.ajax({
                url: '/admin/events/' + info.event.id,
                type: 'PUT',
                data: {
                    start: info.event.start.toISOString(),
                    end: info.event.end ? info.event.end.toISOString() : null,
                    _token: '{{ csrf_token() }}' // Inclua o token CSRF
                },
                success: function (response) {
                    // Sucesso ao atualizar o evento
                },
                error: function (xhr) {
                    // Reverter a mudança se houver um erro
                    info.revert();
                }
            });
        },
     /*    eventClick: function (info) {
            document.getElementById("edit-event-btn").removeAttribute("hidden");
            document.getElementById('btn-save-event').setAttribute("hidden", true);
            document.getElementById("edit-event-btn").setAttribute("data-id", "edit-event");
            document.getElementById("edit-event-btn").innerHTML = "Editar";
            eventClicked();
            flatPickrInit();
            flatpicekrValueClear();
            addEvent.show();
            formEvent.reset();
            selectedEvent = info.event;

            // Primeiro Modal
            document.getElementById("modal-title").innerHTML = "";
            document.getElementById("event-location-tag").innerHTML = selectedEvent.extendedProps.location === undefined ? "Sem Localização" : selectedEvent.extendedProps.location;
            document.getElementById("event-description-tag").innerHTML = selectedEvent.extendedProps.description === undefined ? "Sem Descrição" : selectedEvent.extendedProps.description;

            // Modal de Edição
            document.getElementById("event-title").value = selectedEvent.title;
            document.getElementById("event-location").value = selectedEvent.extendedProps.location === undefined ? "" : selectedEvent.extendedProps.location;
            document.getElementById("event-description").value = selectedEvent.extendedProps.description === undefined ? "" : selectedEvent.extendedProps.description;
            document.getElementById("eventid").value = selectedEvent.id;

            if (selectedEvent.classNames[0]) {
                eventCategoryChoice.destroy();
                eventCategoryChoice = new Choices("#event-category", {
                    searchEnabled: false
                });
                eventCategoryChoice.setChoiceByValue(selectedEvent.classNames[0]);
            }
            var st_date = selectedEvent.start;
            var ed_date = selectedEvent.end;

            // ... (restante do código para manipulação de datas)

            newEventData = null;
            modalTitle.innerText = selectedEvent.title;

            document.getElementById('btn-delete-event').removeAttribute('hidden');
        }, */
        dateClick: function (info) {
            addNewEvent(info);
        },
        eventReceive: function (info) {
            // Se você deseja salvar o evento recebido no servidor, faça uma chamada AJAX aqui
        },
        eventDrop: function (info) {
            // Atualizar o evento no servidor
            $.ajax({
                url: '/admin/events/' + info.event.id,
                type: 'PUT',
                data: {
                    start: info.event.start.toISOString(),
                    end: info.event.end ? info.event.end.toISOString() : null,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    // Sucesso ao atualizar o evento
                },
                error: function (xhr) {
                    info.revert();
                }
            });
        }
    });

    calendar.render();

    // Remova a chamada para upcomingEvent com defaultEvents, pois agora os eventos são carregados via AJAX
    // upcomingEvent(defaultEvents); // Removido

    /*Adicionar novo evento*/
    // Formulário para adicionar novo evento
    formEvent.addEventListener('submit', function (ev) {
        ev.preventDefault();
        var updatedTitle = document.getElementById("event-title").value;
        var updatedCategory = document.getElementById('event-category').value;
        var start_date_input = document.getElementById("event-start-date").value.trim();
        var start_date_array = start_date_input.split("to");
        var start_date = new Date(start_date_array[0].trim());

        var end_date = null;
        if (start_date_array.length > 1) {
            end_date = new Date(start_date_array[1].trim());
        }

        var event_location = document.getElementById("event-location").value;
        var eventDescription = document.getElementById("event-description").value;
        var eventid = document.getElementById("eventid").value;
        var all_day = false;
        var start_time = document.getElementById("timepicker1").value.trim();
        var end_time = document.getElementById("timepicker2").value.trim();

        if (start_time && end_time) {
            start_date.setHours(start_time.split(":")[0], start_time.split(":")[1]);
            if (end_date) {
                end_date.setHours(end_time.split(":")[0], end_time.split(":")[1]);
            } else {
                end_date = new Date(start_date);
                end_date.setHours(end_time.split(":")[0], end_time.split(":")[1]);
            }
        } else {
            all_day = true;
        }

        // Validação
        if (forms[0].checkValidity() === false) {
            forms[0].classList.add('was-validated');
        } else {
            if (selectedEvent) {
                // Atualizar evento existente no servidor
                $.ajax({
                    url: '/admin/events/update/' + selectedEvent.id,
                    type: 'PUT',
                    data: {
                        title: updatedTitle,
                        start: start_date.toISOString(),
                        end: end_date ? end_date.toISOString() : null,
                        className: updatedCategory,
                        description: eventDescription,
                        location: event_location,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        // Atualizar o evento no calendário
                        selectedEvent.setProp("title", updatedTitle);
                        selectedEvent.setProp("classNames", [updatedCategory]);
                        selectedEvent.setStart(start_date);
                        selectedEvent.setEnd(end_date);
                        selectedEvent.setAllDay(all_day);
                        selectedEvent.setExtendedProp("description", eventDescription);
                        selectedEvent.setExtendedProp("location", event_location);

                        calendar.render();
                        addEvent.hide();
                    },
                    error: function (xhr) {
                        // Tratar erros
                    }
                });
            } else {
                // Criar novo evento no servidor
                var eventData = {
                    title: updatedTitle,
                    start: start_date.toISOString(),
                    end: end_date ? end_date.toISOString() : null,
                    className: updatedCategory,
                    description: eventDescription,
                    location: event_location,
                    _token: '{{ csrf_token() }}'
                };

                $.ajax({
                    url: '/admin/events',
                    type: 'POST',
                    data: eventData,
                    success: function (response) {
                        if (response.success) {
                            // Adicionar o evento ao calendário
                            eventData.id = response.event_id;
                            calendar.addEvent(eventData);
                            addEvent.hide();
                            upcomingEvent(); // Atualizar eventos futuros, se necessário
                        }
                    },
                    error: function (xhr) {
                        // Tratar erros
                    }
                });
            }
        }
    });

    document.getElementById("btn-delete-event").addEventListener("click", function (e) {
        if (selectedEvent) {
            $.ajax({
                url: '/admin/events/destroy/' + selectedEvent.id,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.success) {
                        selectedEvent.remove();
                        addEvent.hide();
                    }
                },
                error: function (xhr) {
                    // Tratar erros
                }
            });
        }
    });

    /*  document.getElementById("btn-new-event").addEventListener("click", function (e) {
         flatpicekrValueClear();
         flatPickrInit();
         addNewEvent();
         document.getElementById("edit-event-btn").setAttribute("data-id", "new-event");
         document.getElementById('edit-event-btn').click();
         document.getElementById("edit-event-btn").setAttribute("hidden", true);
     }); */
});

function flatPickrInit() {
    var config = {
        enableTime: true,
        noCalendar: true,
    };
    flatpickr(start_date, {
        enableTime: false,
        mode: "range",
        minDate: "today",
        locale: "br",
        onChange: function (selectedDates, dateStr, instance) {
            var date_range = dateStr;
            var dates = date_range.split("to");
            if (dates.length > 1) {
                document.getElementById('event-time').setAttribute("hidden", true);
            } else {
                document.getElementById("timepicker1").parentNode.classList.remove("d-none");
                document.getElementById("timepicker1").classList.replace("d-none", "d-block");
                document.getElementById("timepicker2").parentNode.classList.remove("d-none");
                document.getElementById("timepicker2").classList.replace("d-none", "d-block");
                document.getElementById('event-time').removeAttribute("hidden");
            }
        },
    });
    flatpickr(timepicker1, config);
    flatpickr(timepicker2, config);
}


function flatpicekrValueClear() {
    start_date._flatpickr.clear();
    timepicker1._flatpickr.clear();
    timepicker2._flatpickr.clear();
}

function eventClicked() {
    document.getElementById('form-event').classList.add("view-event");
    document.getElementById("event-title").classList.replace("d-block", "d-none");
    document.getElementById("event-category").classList.replace("d-block", "d-none");
    document.getElementById("event-start-date").parentNode.classList.add("d-none");
    document.getElementById("event-start-date").classList.replace("d-block", "d-none");
    document.getElementById('event-time').setAttribute("hidden", true);
    document.getElementById("timepicker1").parentNode.classList.add("d-none");
    document.getElementById("timepicker1").classList.replace("d-block", "d-none");
    document.getElementById("timepicker2").parentNode.classList.add("d-none");
    document.getElementById("timepicker2").classList.replace("d-block", "d-none");
    document.getElementById("event-location").classList.replace("d-block", "d-none");
    document.getElementById("event-description").classList.replace("d-block", "d-none");
    document.getElementById("event-start-date-tag").classList.replace("d-none", "d-block");
    document.getElementById("event-timepicker1-tag").classList.replace("d-none", "d-block");
    document.getElementById("event-timepicker2-tag").classList.replace("d-none", "d-block");
    document.getElementById("event-location-tag").classList.replace("d-none", "d-block");
    document.getElementById("event-description-tag").classList.replace("d-none", "d-block");
    document.getElementById('btn-save-event').setAttribute("hidden", true);
}

function editEvent(data) {
    var data_id = data.getAttribute("data-id");
    if (data_id == 'new-event') {
        document.getElementById('modal-title').innerHTML = "";
        document.getElementById('modal-title').innerHTML = "Adicionar Evento";
        document.getElementById("btn-save-event").innerHTML = "Adicionar Evento";
        eventTyped();
    } else if (data_id == 'edit-event') {
        data.innerHTML = "Cancelar";
        data.setAttribute("data-id", 'cancel-event');
        document.getElementById("btn-save-event").innerHTML = "Atualizar Evento";
        data.removeAttribute("hidden");
        eventTyped();
    } else {
        data.innerHTML = "Editar";
        data.setAttribute("data-id", 'edit-event');
        eventClicked();
    }
}

function eventTyped() {
    document.getElementById('form-event').classList.remove("view-event");
    document.getElementById("event-title").classList.replace("d-none", "d-block");
    document.getElementById("event-category").classList.replace("d-none", "d-block");
    document.getElementById("event-start-date").parentNode.classList.remove("d-none");
    document.getElementById("event-start-date").classList.replace("d-none", "d-block");
    document.getElementById("timepicker1").parentNode.classList.remove("d-none");
    document.getElementById("timepicker1").classList.replace("d-none", "d-block");
    document.getElementById("timepicker2").parentNode.classList.remove("d-none");
    document.getElementById("timepicker2").classList.replace("d-none", "d-block");
    document.getElementById("event-location").classList.replace("d-none", "d-block");
    document.getElementById("event-description").classList.replace("d-none", "d-block");
    document.getElementById("event-start-date-tag").classList.replace("d-block", "d-none");
    document.getElementById("event-timepicker1-tag").classList.replace("d-block", "d-none");
    document.getElementById("event-timepicker2-tag").classList.replace("d-block", "d-none");
    document.getElementById("event-location-tag").classList.replace("d-block", "d-none");
    document.getElementById("event-description-tag").classList.replace("d-block", "d-none");
    document.getElementById('btn-save-event').removeAttribute("hidden");
}

// Função para formatar datas no formato DD/MM/AAAA
function formatDate(dateString) {
    var date = new Date(dateString);
    var day = ('0' + date.getDate()).slice(-2);
    var month = ('0' + (date.getMonth() + 1)).slice(-2);
    var year = date.getFullYear();
    return day + '/' + month + '/' + year;
}

// Função para formatar horas no formato HH:MM (24 horas)
function formatTime(dateString) {
    var date = new Date(dateString);
    var hours = date.getHours();
    var minutes = date.getMinutes();
    // Adicionar zero à esquerda, se necessário
    hours = ('0' + hours).slice(-2);
    minutes = ('0' + minutes).slice(-2);
    return hours + ':' + minutes;
}


// Função para atualizar a lista de próximos eventos
function upcomingEvent(events) {

    // Obter a data atual
    var today = new Date();
    var currentMonth = today.getMonth(); // Janeiro é 0, Fevereiro é 1, etc.
    var currentYear = today.getFullYear();

    // Filtrar os eventos para incluir apenas os do mês e ano atuais
    var eventsThisMonth = events.filter(function (event) {
        var eventDate = new Date(event.start); // Usamos 'start' pois definimos 'start' igual a 'end'
        return eventDate.getMonth() === currentMonth && eventDate.getFullYear() === currentYear;
    });

    // Ordenar os eventos por data
    eventsThisMonth.sort(function (a, b) {
        var dateA = new Date(a.start);
        var dateB = new Date(b.start);
        return dateA - dateB; // Ordena em ordem crescente
    });

    // Limpar o conteúdo anterior da div
    document.getElementById("upcoming-event-list").innerHTML = '';

    // Iterar sobre os eventos filtrados
    eventsThisMonth.forEach(function (element) {
        var title = element.title;
        var st_date = element.start ? formatDate(element.start) : null;
        var ed_date = element.end ? formatDate(element.end) : null;
        var end_dt = (ed_date && ed_date !== st_date) ? " até " + ed_date : '';
        var category = (element.classNames && element.classNames.length > 0) ? element.classNames[0].split("-") : ['text', 'warning'];
        var description = (element.extendedProps && element.extendedProps.description) ? element.extendedProps.description : "";
        var e_time_s = element.start ? formatTime(element.start) : '';
        var e_time_e = element.end ? formatTime(element.end) : '';

        if (e_time_s === e_time_e || (!e_time_s && !e_time_e)) {
            e_time_s = "Evento de dia inteiro";
            e_time_e = "";
        } else {
            e_time_e = e_time_e ? " até " + e_time_e : "";
        }

        var u_event = "<div class='card mb-3'>\
                            <div class='card-body'>\
                                <div class='d-flex mb-3'>\
                                    <div class='flex-grow-1'><i class='mdi mdi-checkbox-blank-circle me-2 text-" + category[1] + "'></i><span class='fw-medium'>" + st_date + end_dt + " </span></div>\
                                    <div class='flex-shrink-0'><small class='badge bg-primary-subtle text-primary ms-auto'>" + e_time_s + e_time_e + "</small></div>\
                                </div>\
                                <h6 class='card-title fs-16'> " + title + "</h6>\
                                <p class='text-muted text-truncate-two-lines mb-0'> " + description + "</p>\
                            </div>\
                        </div>";
        document.getElementById("upcoming-event-list").innerHTML += u_event;
    });

    // Se não houver eventos, exibir uma mensagem
    if (eventsThisMonth.length === 0) {
        document.getElementById("upcoming-event-list").innerHTML = '<p>Não há eventos neste mês.</p>';
    }
}


function getTime(date) {
    date = new Date(date);
    if (date.getHours() != null) {
        var hour = date.getHours();
        var minute = (date.getMinutes()) ? date.getMinutes() : 0;
        return hour + ":" + minute;
    }
}

function tConvert(time) {
    var t = time.split(":");
    var hours = t[0];
    var minutes = t[1];
    var newformat = hours >= 12 ? 'PM' : 'AM';
    hours = hours % 12;
    hours = hours ? hours : 12;
    minutes = minutes < 10 ? '0' + minutes : minutes;
    return (hours + ':' + minutes + ' ' + newformat);
}

var str_dt = function formatDate(date) {
    var monthNames = ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho",
        "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"];
    var d = new Date(date),
        month = '' + monthNames[(d.getMonth())],
        day = '' + d.getDate(),
        year = d.getFullYear();
    if (month.length < 2)
        month = '0' + month;
    if (day.length < 2)
        day = '0' + day;
    return [day + " " + month, year].join(', ');
};

// Converter a data para o fuso horário de São Paulo antes de enviar
function convertToSaoPauloTime(date) {
    // Cria um objeto Date com a data fornecida
    var dt = new Date(date);

    // Obtém a diferença de fuso horário entre o UTC e São Paulo em minutos
    var saoPauloOffset = -180; // São Paulo está a -3 horas do UTC (ou -180 minutos)

    // Obtém a diferença de fuso horário local do usuário em minutos
    var localOffset = dt.getTimezoneOffset();

    // Calcula a diferença total
    var totalOffset = localOffset - saoPauloOffset;

    // Ajusta a data para o fuso horário de São Paulo
    dt.setMinutes(dt.getMinutes() + totalOffset);

    return dt;
}

// Ao enviar os dados para o servidor
var startDate = convertToSaoPauloTime(start_date.toISOString());
var endDate = end_date ? convertToSaoPauloTime(end_date.toISOString()) : null;

// Dados a serem enviados
var eventData = {
    title: updatedTitle,
    start: startDate.toISOString(),
    end: endDate ? endDate.toISOString() : null,
    // ... outros dados ...
};


function getEventClassNames(props) {
    let classNames = ['fc-event bg-success'];
    if (props.isMirror) {
        classNames.push('fc-event-mirror');
    }

    return classNames;
}