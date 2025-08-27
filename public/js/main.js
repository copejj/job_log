const pageModal = document.getElementById('pageModal');
const pageContent = document.getElementById('pageContent');
const closeModalBtn = document.getElementById('closeModal');

closeModalBtn.addEventListener('click', () => {
	pageModal.close();
});

// Close modal when clicking outside (for <dialog> element)
pageModal.addEventListener('click', (event) => {
	if (event.target === pageModal) {
		pageModal.close();
	}
});

function openModal(url) {
	fetch(url)
	.then(response => {
		// Check if the request was successful
		if (!response.ok) {
			throw new Error(`HTTP error! status: ${response.status}`);
		}
		// Parse the response as plain text
		return response.text();
	})
	.then(htmlContent => {
		// Set the innerHTML of the target div to the fetched content
		pageContent.innerHTML = htmlContent;
	})
	.catch(error => {
		console.error('Error loading HTML:', error);
	});
	pageModal.showModal();
}

function show_debug(index, rand)
{
	toggle(document.querySelectorAll('.debug_'+rand+'_'+index));
}

function toggle(elements, specifiedDisplay)
{
	var element, index;

	elements = elements.length ? elements : [elements];
	for (index = 0; index < elements.length; index++)
	{
		element = elements[index];

		if (isElementHidden(element))
		{
			element.style.display = '';

			// If the element is still hidden after removing the inline display
			if (isElementHidden(element))
			{
				element.style.display = specifiedDisplay || 'block';
			}
		}
		else
		{
			element.style.display = 'none';
		}
	}

	function isElementHidden (element)
	{
		return window.getComputedStyle(element, null).getPropertyValue('display') === 'none';
	}
}

async function copy_detail_data(btn)
{
	const container = $(btn).closest('tr').find('.detail-control');
	const text = $(btn).closest('tr').find('.detail-input').val();

	$('.detail-control').removeClass('copied');
	try {
		await navigator.clipboard.writeText(text);
		$(container).addClass('copied');
	} catch (err) {
		console.error('failed to copy text: ', err);
		$(container).addClass('error');
	}
}
