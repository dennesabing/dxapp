function alertMessage(msg){
	if(console)
	{
		console.log(msg);
	}
}
function dump(msg){
	alertMessage(msg);
}
function timeZoneOffset() {
	var clientDate = new Date( );
	var gmtOffset = clientDate.getTimezoneOffset() / 60;
	var timezones = {
		'-12': 'Pacific/Kwajalein',
		'-11': 'Pacific/Samoa',
		'-10': 'Pacific/Honolulu',
		'-9': 'America/Juneau',
		'-8': 'America/Los_Angeles',
		'-7': 'America/Denver',
		'-6': 'America/Mexico_City',
		'-5': 'America/New_York',
		'-4': 'America/Caracas',
		'-3.5': 'America/St_Johns',
		'-3': 'America/Argentina/Buenos_Aires',
		'-2': 'Atlantic/Azores',
		'-1': 'Atlantic/Azores',
		'0': 'Europe/London',
		'1': 'Europe/Paris',
		'2': 'Europe/Helsinki',
		'3': 'Europe/Moscow',
		'3.5': 'Asia/Tehran',
		'4': 'Asia/Baku',
		'4.5': 'Asia/Kabul',
		'5': 'Asia/Karachi',
		'5.5': 'Asia/Calcutta',
		'6': 'Asia/Colombo',
		'7': 'Asia/Bangkok',
		'8': 'Asia/Singapore',
		'9': 'Asia/Tokyo',
		'9.5': 'Australia/Darwin',
		'10': 'Pacific/Guam',
		'11': 'Asia/Magadan',
		'12': 'Asia/Kamchatka' 
	};
	document.cookie = 'tz=' + timezones[-gmtOffset];
}
timeZoneOffset();