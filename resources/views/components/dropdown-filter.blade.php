@props([
    'fields' => [],
    'action' => '',
    'formId' => 'filter-form',
    'dateRange' => false,
    'dateOptions' => [],
    'noReset' => false,
    'companyModel' => null,
    'companylabel' => 'Company',
    'valueField' => 'id',
    'labelField' => 'company_name',
    'labelCount' => true,
    'countRelations' => 'addresses',
    'parentName' => 'company_id',
    'parentUrl' => 'api/fetch-companies',
    'childName' => 'distributor_id',
    'childUrl' => 'api/fetch-locations',
    'childInputLabel' => 'Location',
    'childLabel' => 'location',
    'childValue' => 'id',
    'childType' => 'select',
    'childRequired' => false,
    'hasChild' => true,
])

<div class="dropdown">
	<button class="btn btn-info dropdown-filter-btn dropdown-toggle"
		data-bs-toggle="dropdown"
		data-bs-auto-close="outside"
		type="button"
		aria-expanded="false">
		Filter <i class="bi bi-funnel"></i>
	</button>
	<div class="dropdown-menu dropdown-filter-menu">
		@include('components.filter-form', [
			'fields' => $fields,
			'action' => $action,
			'formId' => $formId,
			'dateRange' => $dateRange,
			'dateOptions' => $dateOptions,
			'noReset' => $noReset,
			'companyModel' => $companyModel,
			'companylabel' => $companylabel,
			'valueField' => $valueField,
			'labelField' => $labelField,
			'labelCount' => $labelCount,
			'countRelations' => $countRelations,
			'childName' => $childName,
			'childValue' => $childValue,
			'childLabel' => $childLabel,
			'childType' => $childType,
			'childRequired' => $childRequired,
			'hasChild' => $hasChild,
		])
	</div>
</div>
