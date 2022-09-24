//Magic code which lets ts see vue files or something, IDK, but without it npm won't compile.
declare module "*.vue" {
    import Vue from 'vue'
    export default Vue
}