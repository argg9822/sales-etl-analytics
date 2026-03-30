let CACHE_DATA = null;

export function queryData() {
    const routeUrl = route('imports.index');
    axios.get(routeUrl)
        .then(response => {
            if(response.data && response.data.data && response.data.data.imports) {
                if(JSON.stringify(CACHE_DATA) !== JSON.stringify(response.data.data.imports)) {
                    CACHE_DATA = response.data.data.imports;
                    table(response.data.data.imports);
                }
            } else {
                    table([]);
            }
        })
        .catch(error => {
            console.error(error);
        });
}

function iconsMap(status){
    const icons = {
        'processing': `<i class="fa-solid fa-circle fa-fade status-${status}"></i>`,
        'completed': `<i class="fa-solid fa-circle-check status-${status}"></i>`,
        'failed': `<i class="fa-solid fa-circle-xmark status-${status}"></i>`,
        'completed-errors': `<i class="fa-solid fa-circle-exclamation status-${status}"></i>`
    };
    return icons[status] || '';
}

function translationStatus(status){
    const translations = {
        'processing': 'En proceso',
        'completed': 'Completado',
        'failed': 'Fallido',
        'completed-errors': 'Completado con errores'
    };
    return translations[status] || status;
}

function table(data){
    const tableBody = document.getElementById('imports-table-body');
    
    const rows = data.length > 0 
            ?
                data.map(importRecord => {
                    return `
                        <tr>
                            <td>${importRecord.file_name}</td>
                            <td>${formatDate(importRecord.created_at)}</td>
                            <td>${importRecord.total_records || '-'}</td>
                            <td>${importRecord.processed_records || '-'}</td>
                            <td>${importRecord.errors_count || '-'}</td>
                            <td>
                                <span class="status-badge status-${importRecord.status}" title="${translationStatus(importRecord.status)}">
                                    ${iconsMap(importRecord.status)}
                                </span>

                            </td>
                            <td>
                                <a href="${route('imports.show.web', importRecord.id)}" target="_blank" title="Ver detalles">
                                    <i class="fa-solid fa-eye" style="color: #00b4d8;"></i>
                                </a>
                            </td>
                        </tr>
                    `;
                }).join('')
            : 
                `<tr><td colspan="7">No se han encontrado importaciones.</td></tr>`;
    tableBody.innerHTML = rows;
}

function formatDate(dateString) {
    const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
    return new Date(dateString).toLocaleDateString(undefined, options);
}