(function() {

    // fetch json data helper function
    async function fetchJSON(url) {
        const req = new Request(url, {
            method: 'GET',
            headers: {'Content-Type': 'application/json'}
        });

        const res = await fetch(req);
        const data = await res.json();
    
        return data;
    }

    // get random key from object
    function randomObjectKey(obj) {
        const keys = Object.keys(obj);
        return keys[Math.random() * keys.length << 0];
    }

    // remove trailing path characters
    function removeTralingPathSegments(str) {
        return str.replace(/[/\\]{1,2}$/, '');
    }

    // display current index path
    (async function() {
        let path = await fetchJSON('/localhost-index/controllers/get_doc_root.php') + decodeURI(document.location.pathname);
        path = path.split(/[/\\]{1,3}/).join('<span class="path-delimiter">/</span>');

        document.getElementById('index-path').innerHTML = path;
    }());

    // random quote picking
    (async function() {
        const quotes = await fetchJSON('/localhost-index/assets/quotes.json');
        const section = randomObjectKey(quotes);
        const quote = randomObjectKey(quotes[section]);
        const author = quotes[section][quote];

        document.getElementById('quote').textContent = quote;
        document.getElementById('author').textContent = author;

    }());

    const table = document.getElementById('indexlist');
    
    // remove trailing '/' characters from folder names
    document.querySelectorAll('#indexlist .indexcolname a').forEach(element => {
        element.textContent = removeTralingPathSegments(element.textContent);
    });

    // create table footer > display apache verion + add open in explorer button
    const tFooterRow = table.createTFoot().insertRow();
    
    (async function() {
        let serverInfo = await fetchJSON('/localhost-index/controllers/get_apache_info.php');
        const serverInfoCell = tFooterRow.insertCell();
        serverInfoCell.setAttribute('id', 'server-info');
        serverInfoCell.setAttribute('colspan', '2');
        serverInfoCell.textContent = serverInfo;

        const openExplorerCell = tFooterRow.insertCell();
        openExplorerCell.setAttribute('id', 'open-explorer');
        openExplorerCell.setAttribute('colspan', '2');
        openExplorerCell.innerHTML = '<a title="Open in explorer" href="#"><img src="/localhost-index/assets/icons/open-folder.svg" alt="open"></a>';

        openExplorerCell.querySelector('a').addEventListener('click', function(event) {
                event.preventDefault()
    
                fetch('/localhost-index/controllers/open_root.php?p=' + window.location.pathname)
        });
    }());

    // search bar
    function filter(keyword) {

            document.querySelectorAll('td.indexcolname').forEach(element => {
                if (keyword) {
                    if (element.textContent.toLowerCase().indexOf(keyword.toLowerCase()) === 0) {
                        // show matched row
                        element.parentElement.classList.remove('hidden')
                    } else {
                        // hide unmatched row
                        element.parentElement.classList.add('hidden')
                    }     
                } else {
                    element.parentElement.classList.remove('hidden')
                }
            });
    }

    const search = document.getElementById('search-keywords');

    search.addEventListener('input', (field) => {
        filter(field.target.value)
    })

    // hide blank th icon
    document.querySelector('th.indexcolicon').remove();
    document.querySelector('th.indexcolname').setAttribute('colspan', '2');

})();