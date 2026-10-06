const renderProgramCard = (program) => {
	const status = program.status;

	return `
		<article class="wa__card">
			<h3 class="wa__card__title">${program.title}</h3>

			<dl class="wa__card__details">
				<div>
					<dt>Helyszín</dt>
					<dd>${program.location}</dd>
				</div>

				<div>
					<dt>Időpont</dt>
					<dd>${program.start_at}</dd>
				</div>

				<div>
					<dt>Nehézség</dt>
					<dd>${program.difficulty ?? 'Nincs megadva'}</dd>
				</div>

				<div>
					<dt>Ár</dt>
					<dd>${program.price_huf}</dd>
				</div>
			</dl>

			<p class="wa__card__status wa__card__status--${status.key}">
				${status.label}
			</p>

			${status.bookable
			? '<button type="button" class="wa__card__cta">Foglalás</button>'
			: '<button type="button" class="wa__card__cta" disabled>Nem foglalható</button>'
		}
		</article>
	`;
};

const programList = ($) => {
	const container = $('.js-programs-container');

	if (!container.length) return;

	const difficultyFilter = $('.js-filter');
	const difficultyFilterWrapper = $('.js-filter-wrapper');

	let programs = [];

	$.ajax({
		url: '/wp-json/wa/v1/programs',
		method: 'GET',
		success: function (data) {
			programs = data;

			if (!programs.length) {
				container.html('<p class="wa__empty">Jelenleg nincs elérhető program.</p>');
				return;
			}

			difficultyFilterWrapper.css("display", "flex")
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

	difficultyFilter.on('change', function () {
		const selectedDifficulty = $(this).val();

		const filteredPrograms = selectedDifficulty
			? programs.filter((program) => program.difficulty === selectedDifficulty)
			: programs;

		if (!filteredPrograms.length) {
			container.html(
				'<p class="wa__empty">Nincs a szűrésnek megfelelő program.</p>'
			);

			return;
		}

		container.html(
			filteredPrograms.map(renderProgramCard).join('')
		);
	});
};

programList(jQuery);