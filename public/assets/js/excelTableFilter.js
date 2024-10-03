(function ($) {
    'use strict';

    class FilterMenu {
        constructor(target, th, column, index, options) {
            this.options = options;
            this.th = th;
            this.column = column;
            this.index = index;
            this.tds = target.find(`tbody tr td:nth-child(${this.column + 1})`).toArray();
        }

        initialize() {
            this.menu = this.dropdownFilterDropdown();
            this.th.appendChild(this.menu);
            const $trigger = $(this.menu).find('.dropdown-filter-icon');
            const $content = $(this.menu).find('.dropdown-filter-content');
            const $menu = $(this.menu);

            $trigger.on('click', () => $content.toggle());

            $(document).on('click', (event) => {
                if (!$menu.is(event.target) && $menu.has(event.target).length === 0) {
                    $content.hide();
                }
            });
        }

        searchToggle(value) {
            if (this.selectAllCheckbox) this.selectAllCheckbox.checked = false;
            if (value.length === 0) {
                this.toggleAll(true);
                if (this.selectAllCheckbox) this.selectAllCheckbox.checked = true;
                return;
            }
            this.toggleAll(false);
            this.inputs
                .filter(input => input.value.toLowerCase().includes(value.toLowerCase()))
                .forEach(input => input.checked = true);
        }

        updateSelectAll() {
            if (this.selectAllCheckbox) {
                $(this.searchFilter).val('');
                this.selectAllCheckbox.checked = this.inputs.every(input => input.checked);
            }
        }

        selectAllUpdate(checked) {
            $(this.searchFilter).val('');
            this.toggleAll(checked);
        }

        toggleAll(checked) {
            // Use jQuery's .prop() method to ensure proper updating of checkboxes
            this.inputs.forEach(input => $(input).prop('checked', checked));
        }

        createFilterItem(value, className = 'item') {
            // Ensure value is a string before calling trim()
            const trimmedValue = (typeof value === 'string') ? value.trim() : '';
            if (trimmedValue === '' && className !== 'select-all') {
                // Avoid adding empty items, except for 'select-all'
                return null;
            }

            const itemDiv = $('<div>', { class: 'dropdown-filter-item' });
            const input = $('<input>', {
                type: 'checkbox',
                value: trimmedValue,
                checked: true,
                class: `dropdown-filter-menu-item ${className}`,
                'data-column': this.column,
                'data-index': this.index
            });
            itemDiv.append(input).append(` ${trimmedValue}`);
            return itemDiv.get(0);
        }

        dropdownFilterContent() {
            // Ensure textContent is not undefined
            const uniqueValues = [...new Set(this.tds.map(td => (td.textContent || '').trim()))].sort((a, b) => {
                if (!isNaN(a) && !isNaN(b)) {
                    return a - b;
                }
                return a.localeCompare(b);
            });

            // Filter out empty values
            const validUniqueValues = uniqueValues.filter(value => value !== '');

            const filterItems = validUniqueValues.map(value => this.createFilterItem(value)).filter(item => item !== null);
            this.inputs = filterItems.map(item => $(item).find('input')[0]);

            const selectAllItem = this.createFilterItem(this.options.captions.select_all, 'select-all');
            this.selectAllCheckbox = $(selectAllItem).find('input')[0];

            const checkboxContainer = $('<div>', { class: 'checkbox-container' })
                .append(selectAllItem, ...filterItems);

            const elements = [];
            if (this.options.sort) {
                elements.push(this.dropdownFilterSort(this.options.captions.a_to_z, 'a-to-z', 'ascendente'));
                elements.push(this.dropdownFilterSort(this.options.captions.z_to_a, 'z-to-a', 'descendente'));
            }
            if (this.options.search) {
                const searchDiv = this.dropdownFilterSearch();
                this.searchFilter = $(searchDiv).find('input')[0];
                elements.push(searchDiv);
            }
            elements.push(checkboxContainer.get(0));

            const contentDiv = $('<div>', { class: 'dropdown-filter-content' });
            elements.forEach(el => contentDiv.append(el));
            return contentDiv.get(0);
        }

        dropdownFilterSearch() {
            const searchDiv = $('<div>', { class: 'dropdown-filter-search' });
            const input = $('<input>', {
                type: 'text',
                class: 'dropdown-filter-menu-search form-control',
                'data-column': this.column,
                'data-index': this.index,
                placeholder: this.options.captions.search
            });
            searchDiv.append(input);
            return searchDiv.get(0);
        }

        dropdownFilterSort(direction, orderClass, sortClass) {
            const sortDiv = $('<div>', {
                class: `dropdown-filter-sort ${sortClass}`,
                'data-order': orderClass,
                'data-column': this.column,
                'data-index': this.index
            }).html(direction);
            return sortDiv.get(0);
        }

        dropdownFilterDropdown() {
            const dropdownDiv = $('<div>', { class: 'dropdown-filter-dropdown' });
            const arrow = $('<span>', { class: 'dropdown-filter-icon' })
                .append($('<i>', { class: 'mdi mdi-arrow-down-bold-box mdi-18px' }));
            dropdownDiv.append(arrow).append(this.dropdownFilterContent());

            if ($(this.th).hasClass('no-sort')) {
                dropdownDiv.find('.dropdown-filter-sort').remove();
            }
            if ($(this.th).hasClass('no-filter')) {
                dropdownDiv.find('.checkbox-container').remove();
            }
            if ($(this.th).hasClass('no-search')) {
                dropdownDiv.find('.dropdown-filter-search').remove();
            }
            return dropdownDiv.get(0);
        }
    }

    class FilterCollection {

        constructor(target, options) {
            this.target = target;
            this.options = options;

            this.ths = target.find('th' + options.columnSelector).toArray();

            this.filterMenus = this.ths.map((th, index) => {
                const $th = $(th);
                const column = $th.index();

                // Check if the column has 'no-filter' or 'no-sort' class
                if ($th.hasClass('no-filter') || $th.hasClass('no-sort')) {
                    return null; // Skip this column
                }

                return new FilterMenu(target, th, column, index, options);
            }).filter(menu => menu !== null); // Remove null items

            this.allRows = target.find('tbody tr').toArray(); // All rows, including hidden ones
            this.table = target[0];
            this.currentPage = 1;
            this.filteredRows = $(this.allRows); // Initialize filteredRows with all rows

            if (options.pagination) {
                if (options.paginationContainer) {
                    this.paginationContainer = $(options.paginationContainer);
                } else {
                    this.paginationContainer = $('<div class="pagination-container" id="pagination-container"></div>');
                    this.target.after(this.paginationContainer);
                }
            }

            this.initialize();
        }

        async exportToExcel(filename = 'funcoes_funcionarios.xlsx') {
            // 1. Capturar os cabeçalhos da tabela, excluindo o último
            /* const headers = this.ths.map(function () {
                return $(this).clone()
                    .children()
                    .remove()
                    .end()
                    .text().trim();
            }).slice(0, -1);
 */
            // 2. Obter os dados das linhas filtradas, excluindo a última coluna
            const data = [];
            this.filteredRows.each(function () {
                const row = [];
                const cells = $(this).find('td').slice(0, -1); // Exclui o último 'td'
                cells.each(function () {
                    let cellText = $(this).text().trim();

                    // Tentar converter para número
                    let cellValue = parseFloat(cellText);
                    if (!isNaN(cellValue)) {
                        row.push(cellValue); // Valor numérico
                    } else {
                        row.push(cellText); // Texto original
                    }
                });
                data.push(row);
            });

            // 3. Carregar o arquivo Excel modelo
            const arrayBuffer = await this.loadTemplate();
            const workbook = await XlsxPopulate.fromDataAsync(arrayBuffer);
            const sheet = workbook.sheet(0); // Seleciona a primeira planilha; ajuste se necessário

            // 4. Inserir os dados no arquivo modelo
            const startRow = 4; // Ajuste conforme necessário
            const startCol = 1; // Ajuste conforme necessário

            // Inserir os cabeçalhos na linha anterior, se desejar
            /*    headers.forEach((header, index) => {
                   sheet.cell(startRow - 1, startCol + index)
                       .value(header)
                       .style({
                           bold: true,
                           border: {
                               left: "thin",
                               right: "thin",
                               top: "thin",
                               bottom: "thin"
                           }
                       });
               }); */

            // Inserir os dados e aplicar bordas
            data.forEach((rowData, rowIndex) => {
                rowData.forEach((cellData, colIndex) => {
                    const cell = sheet.cell(startRow + rowIndex, startCol + colIndex);
                    cell.value(cellData)
                        .style("border", {
                            left: "thin",
                            right: "thin",
                            top: "thin",
                            bottom: "thin"
                        });

                    // Se o valor é um número, aplicar formato numérico
                    if (typeof cellData === 'number') {
                        cell.style("numberFormat", "#,##0"); // Formato de número inteiro
                    }
                });
            });
            // 5. Inserir a linha de total
            const totalRows = data.length;
            const totalRowNumber = startRow + data.length;
            const numColumns = data[0] ? data[0].length : 1;

            // Inserir o texto "TOTAL= x" na primeira célula da linha de total
            sheet.cell(totalRowNumber, startCol)
                .value(`TOTAL= ${totalRows}`)
                .style({
                    bold: true,
                    horizontalAlignment: 'center',
                    border: {
                        left: "thin",
                        right: "thin",
                        top: "thin",
                        bottom: "thin"
                    }
                });

            // Mesclar as células da linha de total
            sheet.range(
                sheet.cell(totalRowNumber, startCol),
                sheet.cell(totalRowNumber, startCol + numColumns - 1)
            ).merged(true);

            // Aplicar bordas às células mescladas
            sheet.range(
                sheet.cell(totalRowNumber, startCol),
                sheet.cell(totalRowNumber, startCol + numColumns - 1)
            ).style("border", {
                left: "thin",
                right: "thin",
                top: "thin",
                bottom: "thin"
            });

            // 6. Aplicar bordas ao redor de todo o conjunto de dados
            const dataRange = sheet.range(
                sheet.cell(startRow - 1, startCol),
                sheet.cell(totalRowNumber, startCol + numColumns - 1)
            );
            dataRange.style("border", {
                left: "thin",
                right: "thin",
                top: "thin",
                bottom: "thin"
            });

            // 7. Gerar o arquivo Excel e iniciar o download
            const blob = await workbook.outputAsync();

            if (window.navigator && window.navigator.msSaveOrOpenBlob) {
                // Para IE
                window.navigator.msSaveOrOpenBlob(blob, filename);
            } else {
                const url = URL.createObjectURL(blob);
                const a = document.createElement("a");
                document.body.appendChild(a);
                a.href = url;
                a.download = filename;
                a.click();
                URL.revokeObjectURL(url);
                document.body.removeChild(a);
            }
        }



        // Função para carregar o arquivo Excel modelo
        async loadTemplate() {
            const response = await fetch('/assets/components/tabelas/template.xlsx');
            if (!response.ok) {
                throw new Error(`Erro ao carregar o template: ${response.statusText}`);
            }
            const arrayBuffer = await response.arrayBuffer();
            return arrayBuffer;
        }


        initialize() {
            this.filterMenus.forEach(menu => menu.initialize());
            this.bindEvents();

            // Create the rows per page selector if enabled
            if (this.options.pagination && this.options.rowsPerPageSelector) {
                this.createRowsPerPageSelector();
            }

            if (this.options.pagination) {
                this.updateRowVisibility(); // Initialize visibility and pagination
            } else {
                this.filteredRows.show();
            }
        }

        createRowsPerPageSelector() {
            const self = this;
            const options = this.options;

            const $selector = $('<select class="form-select form-control-sm">', {
                class: 'excel-filter-rows-per-page form-select',
                id: 'excel-filter-rows-per-page-' + this.target.attr('id')
            });

            options.rowsPerPageOptions.forEach(function (value) {
                const $option = $('<option>', {
                    value: value,
                    text: value + ' linhas por página',
                    selected: value == options.rowsPerPage
                });
                $selector.append($option);
            });

            let $container;
            if (options.rowsPerPageSelectorContainer) {
                $container = $(options.rowsPerPageSelectorContainer);
            } else {
                $container = $('<div>', { class: 'excel-filter-rows-per-page-container' });
                this.target.before($container);
            }

            const $label = $('<label>', {
                for: 'excel-filter-rows-per-page-' + this.target.attr('id'),
                text: 'Linhas por página:',
                class: 'me-2 filter-label'
            });

            const $selectorWrapper = $('<div>', { class: 'd-flex align-items-center mb-3' });
            $selectorWrapper.append($label).append($selector);
            $container.append($selectorWrapper);

            $selector.on('change', function () {
                const newRowsPerPage = parseInt($(this).val(), 10) || options.rowsPerPage;
                self.updateRowsPerPage(newRowsPerPage);
            });
        }

        updateRowsPerPage(newRowsPerPage) {
            this.options.rowsPerPage = newRowsPerPage;
            this.currentPage = 1;
            this.paginate();
        }

        bindEvents() {
            const updateRowVisibility = () => {
                this.updateRowVisibility();
            };

            const filterMenus = this.filterMenus;

            this.target.on('change', '.dropdown-filter-menu-item.item', (event) => {
                const index = $(event.currentTarget).data('index');
                filterMenus[index].updateSelectAll();
                updateRowVisibility();
            });

            this.target.on('change', '.dropdown-filter-menu-item.select-all', (event) => {
                const index = $(event.currentTarget).data('index');
                const checked = event.currentTarget.checked;
                filterMenus[index].selectAllUpdate(checked);
                updateRowVisibility();
            });

            this.target.on('click', '.dropdown-filter-sort', (event) => {
                const $sortDiv = $(event.currentTarget);
                const column = $sortDiv.data('column');
                const order = $sortDiv.data('order');
                this.sort(column, order);
                const $dropdownContent = $sortDiv.closest('.dropdown-filter-content');
                $dropdownContent.find('.dropdown-filter-sort').removeClass('active');
                $sortDiv.addClass('active');
                updateRowVisibility();
            });

            this.target.on('keyup', '.dropdown-filter-search input', (event) => {
                const index = $(event.currentTarget).data('index');
                const value = $(event.currentTarget).val();
                filterMenus[index].searchToggle(value);
                updateRowVisibility();
            });
        }

        updateRowVisibility() {
            const selectedLists = this.filterMenus.map(menu => ({
                column: menu.column,
                selected: menu.inputs.filter(input => input.checked).map(input => input.value.trim())
            }));

            // Store the filtered rows in a jQuery object
            let filteredRows = $();

            this.allRows.forEach(row => {
                const $row = $(row);
                const tds = $row.find('td');
                const isVisible = selectedLists.every(({ column, selected }) => {
                    const cellText = tds.eq(column).text().trim();
                    return selected.includes(cellText);
                });
                if (isVisible) {
                    filteredRows = filteredRows.add($row); // Add the row to the filtered set
                }
                $row.hide(); // Hide all rows initially
            });

            this.filteredRows = filteredRows; // Store the filtered rows for later use

            if (this.options.pagination) {
                this.currentPage = 1; // Reset to the first page
                this.paginate();
            } else {
                filteredRows.show();
            }
        }

        paginate() {
            const $paginationContainer = this.paginationContainer;
            const filteredRows = this.filteredRows; // Use the stored filtered rows
            const totalRows = filteredRows.length;
            const rowsPerPage = this.options.rowsPerPage;
            const totalPages = Math.ceil(totalRows / rowsPerPage) || 1; // Evita divisão por zero

            if (this.currentPage > totalPages) {
                this.currentPage = totalPages;
            }
            if (this.currentPage < 1) {
                this.currentPage = 1;
            }

            // Remover o conteúdo anterior do contêiner de paginação
            $paginationContainer.empty();

            // Criar ou selecionar a div 'registers' para exibir as informações de total
            let $registersDiv = $paginationContainer.find('#registers');
            if ($registersDiv.length === 0) {
                $registersDiv = $('<div id="registers" class="registers-info"></div>');
                $paginationContainer.append($registersDiv);
            }
            // Atualizar o conteúdo da div 'registers'
            $registersDiv.html(`Total de registros: <strong>${totalRows}</strong>, Total de pág: <strong>${totalPages}</strong>`);

            // Criar ou selecionar a div 'pagination_table' para os botões de paginação
            let $paginationTableDiv = $paginationContainer.find('#pagination_table');
            if ($paginationTableDiv.length === 0) {
                $paginationTableDiv = $('<div id="pagination_table" class="pagination-controls"></div>');
                $paginationContainer.append($paginationTableDiv);
            } else {
                $paginationTableDiv.empty(); // Limpar conteúdo anterior
            }

            if (totalPages > 1) {
                // Botão "Anterior"
                const prevBtn = $('<button class="prev-paginate-btn">')
                    .text(this.options.captions.prevPaginateBtn || 'Anterior')
                    .prop('disabled', this.currentPage === 1)
                    .on('click', () => {
                        if (this.currentPage > 1) {
                            this.currentPage--;
                            this.renderPage();
                            this.paginate();
                        }
                    });
                $paginationTableDiv.append(prevBtn);

                // Mostrar um máximo de 5 páginas na paginação
                const maxPagesToShow = 5;
                let startPage = Math.max(this.currentPage - Math.floor(maxPagesToShow / 2), 1);
                let endPage = Math.min(startPage + maxPagesToShow - 1, totalPages);

                if (endPage - startPage + 1 < maxPagesToShow) {
                    startPage = Math.max(endPage - maxPagesToShow + 1, 1);
                }

                for (let i = startPage; i <= endPage; i++) {
                    const pageButton = $('<button class="btn-pagination">')
                        .text(i)
                        .addClass(i === this.currentPage ? 'active' : '')
                        .on('click', () => {
                            this.currentPage = i;
                            this.renderPage();
                            this.paginate();
                        });
                    $paginationTableDiv.append(pageButton);
                }

                // Botão "Próximo"
                const nextBtn = $('<button class="next-paginate-btn">')
                    .text(this.options.captions.nextPaginateBtn || 'Próximo')
                    .prop('disabled', this.currentPage === totalPages)
                    .on('click', () => {
                        if (this.currentPage < totalPages) {
                            this.currentPage++;
                            this.renderPage();
                            this.paginate();
                        }
                    });
                $paginationTableDiv.append(nextBtn);
            }

            this.renderPage();
        }


        renderPage() {
            const start = (this.currentPage - 1) * this.options.rowsPerPage;
            const end = this.currentPage * this.options.rowsPerPage;
            const filteredRows = this.filteredRows;

            filteredRows.hide();
            filteredRows.slice(start, end).show();
        }

        sort(column, order) {
            const flip = order === 'z-to-a' ? -1 : 1;
            const tbody = $(this.table).find('tbody').get(0);
            const rows = Array.from(this.allRows);

            rows.sort((a, b) => {
                const A = a.children[column].innerText.toUpperCase();
                const B = b.children[column].innerText.toUpperCase();
                if (!isNaN(Number(A)) && !isNaN(Number(B))) {
                    return (Number(A) - Number(B)) * flip;
                }
                return A.localeCompare(B) * flip;
            });

            rows.forEach(row => tbody.appendChild(row));
            this.allRows = rows; // Update the list of all rows after sorting

            this.updateRowVisibility();
        }

        // Optional: Add a destroy method if needed
        destroy() {
            // Unbind events
            this.target.off('.excelTableFilter');

            // Remove added elements
            this.target.find('.dropdown-filter-dropdown').remove();
            if (this.paginationContainer) {
                this.paginationContainer.empty();
            }
            // Remove rows per page selector
            this.target.prev('.excel-filter-rows-per-page-container').remove();

            // Show all rows
            $(this.allRows).show();

            // Remove data
            this.target.removeData('excelTableFilter');
        }
    }

    $.fn.excelTableFilter = function (optionsOrMethod) {
        if (typeof optionsOrMethod === 'string') {
            var method = optionsOrMethod;
            var args = Array.prototype.slice.call(arguments, 1);

            return this.each(function () {
                var $this = $(this);
                var instance = $this.data('excelTableFilter');

                if (instance && typeof instance[method] === 'function') {
                    instance[method].apply(instance, args);
                }
            });
        } else {
            optionsOrMethod = optionsOrMethod || {};
            // Use deep merge to ensure nested properties are merged correctly
            var options = $.extend(true, {
                pagination: false,
                rowsPerPage: 10,
                rowsPerPageSelector: false,
                rowsPerPageOptions: [5, 10, 20, 50, 100],
                rowsPerPageSelectorContainer: null,
                paginationContainer: null,
                columnSelector: '',
                sort: true,
                search: true,
                captions: {
                    a_to_z: '<i class="mdi mdi-order-alphabetical-ascending"></i> <i class="mdi mdi-arrow-down-thin"></i><span class="ascendente">Classificar de <u>A</u> até Z</span>',
                    z_to_a: '<i class="mdi mdi-order-alphabetical-descending"></i> <i class="mdi mdi-arrow-down-thin"></i><span class="descendente">Classificar de <u>Z</u> até A</span>',
                    search: 'Pesquisar',
                    select_all: 'Selecionar Todos',
                    prevPaginateBtn: 'Anterior',
                    nextPaginateBtn: 'Próximo'
                }
            }, optionsOrMethod);

            return this.each(function () {
                var $this = $(this);
                var instance = $this.data('excelTableFilter');

                if (!instance) {
                    instance = new FilterCollection($this, options);
                    $this.data('excelTableFilter', instance);
                }
            });
        }
    };

})(jQuery);
