const renderProgramCard = (program) => {
	const status = program.status;

	return `
		<article class="wa__card__program">
			<h3>${program.title}</h3>

			<p>${program.location}</p>
			<p>${program.start_at}</p>
			<p>${program.difficulty ?? '-'}</p>
			<p>${program.price_huf}</p>

			<div class="wa__status wa__status--${status.key}">
				${status.label}
			</div>

			${status.bookable
			? '<button type="button">Foglalás</button>'
			: '<button type="button" disabled>Nem foglalható</button>'
		}
		</article>
	`;
};

const programList = ($) => {
	const container = $('.js-programs-container');

	if (!container.length) return;

	$.ajax({
		url: '/wp-json/wa/v1/programs',
		method: 'GET',
		success: function (programs) {
			if (!programs.length) {
				container.html(
					'<p class="wa__empty">Jelenleg nincs elérhető program.</p>'
				);

				return;
			}

			container.html(
				programs.map(renderProgramCard).join('')
			);
		},
		error: function (xhr) {
			console.error(xhr);

			container.html(
				'<p class="wa__error">A programok betöltése sikertelen. Kérjük, próbáld újra később.</p>'
			);
		},
	});
};

programList(jQuery);