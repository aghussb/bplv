let element = `
<div id="blockuiManual" class="p-blockui p-component-overlay p-component-overlay-enter p-blockui-document" style="z-index: 123456789;"></div>
`;


const loading = () => {
    document.body.insertAdjacentHTML('beforeEnd', element);
}

const unloading = () => {
    document.getElementById("blockuiManual").remove();
}

export {
    loading,
    unloading
};