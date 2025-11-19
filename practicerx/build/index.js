(function (wp) {
    var element = wp.element.createElement;
    var render = wp.element.render;

    function App() {
        return element('div', { className: 'practicerx-page' },
            element('h1', null, 'PracticeRx'),
            element('p', null, 'Please run "npm run build" to compile the full React application.')
        );
    }

    var root = document.getElementById('practicerx-root');
    if (root) {
        render(element(App), root);
    }
})(window.wp);
