$(document).ready(function () {
	const cookies = document.cookie.split('; ');
	if(cookies.some((item) => item.trim().startsWith('cookiestorageMethod='))){
		if(cookies.some((item) => item.trim().startsWith('cookiestorageCookies='))){
			const cookiesToUse = cookies.find((item) => item.startsWith('cookiestorageCookies=')).split('=')[1].split(', ');
			const method = cookies.find((item) => item.startsWith('cookiestorageMethod=')).split('=')[1];
			switch (method) {
				case "idle":
					console.log('[cookiestorage] idling while waiting for cookies to bake in the oven');
					break;
				case "remove":
					cookiesToUse.forEach((item) => removeCookie(item));
					console.log('[cookiestorage] ate cookies out of the jar');
					break;
				case "restore":
					cookiesToUse.forEach((item) => (cookies.some((oItem) => oItem.startsWith(item + '=')) ? idle() : restoreCookie(item)));
					console.log('[cookiestorage] made a picture of the cookies in the jar');
					break;
				case "store":
					cookiesToUse.forEach((item) => storeCookie(item));
					console.log('[cookiestorage] put cookies in the jar');
					break;
				default:
					console.log('[cookiestorage] received an unknown instruction');
					break;
			}

			document.cookie = "cookiestorageMethod=; path=/ ;expires= Thu, 01 Jan 1970 00:00:01 GMT";
			document.cookie = "cookiestorageCookies=; path=/ ;expires= Thu, 01 Jan 1970 00:00:01 GMT";
		}
	}
});

function idle(){

}

function removeCookie(name) {
	localStorage.removeItem(name);
}

function restoreCookie(name) {
	if(localStorage.getItem(name)){
		const exp = new Date();
		exp.setTime(exp.getTime + 30*24*60*60*1000);
		document.cookie = name + "=" + localStorage.getItem(name) + "; path=/ ;expires=" + exp.toUTCString + "; SameSite=Strict; Secure";
	}
}

function storeCookie(name, content) {
	localStorage.setItem(name, content);
}