if(page === 'create') {
    import('./imports.js').then(module => {
        module.sendFile();
        module.UIEvents();
    });
}

if(page === 'index') {
    import('./index.js').then(module => {
        setInterval(() => {
            module.queryData();
        }, 5000);
        module.queryData();
    });
}