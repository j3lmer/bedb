export class commonGameViewHelper {
    public static getSteamAppId(): number {
        const windowLocation = window.location.pathname;
        const temp = windowLocation.substring(windowLocation.lastIndexOf('/') + 1);
        return +temp;
    }
}