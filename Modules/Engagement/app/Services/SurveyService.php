<?php

namespace Modules\Engagement\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Modules\Engagement\Models\Survey;
use Modules\Engagement\Models\SurveyLog;

class SurveyService
{
    public function list(array $filters = []): Collection
    {
        $query = Survey::query();

        if (isset($filters['campus_id'])) {
            $query->where(function ($q) use ($filters) {
                $q->whereNull('campus_id')
                    ->orWhere('campus_id', $filters['campus_id']);
            });
        }

        if (isset($filters['active_only']) && $filters['active_only']) {
            $query->active();
        }

        if (isset($filters['target_type'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('target_type', 'ALL')
                    ->orWhere('target_type', $filters['target_type']);
            });
        }

        return $query->latest()->get();
    }

    public function create(array $data): Survey
    {
        return Survey::create($data);
    }

    public function update(int $id, array $data): Survey
    {
        $survey = Survey::findOrFail($id);
        $survey->update($data);
        return $survey;
    }

    public function delete(int $id): bool
    {
        $survey = Survey::find($id);
        if (!$survey) {
            return false;
        }
        return $survey->delete();
    }

    public function getActiveForUser(Model $user): Collection
    {
        return Survey::forUser($user)->latest()->get();
    }

    public function logParticipation(int $surveyId, Model $participant): SurveyLog
    {
        $survey = Survey::findOrFail($surveyId);
        $type = $survey->mapModelToTargetType($participant);

        $log = SurveyLog::where('survey_id', $surveyId)
            ->where('participant_type', $type)
            ->where('participant_id', $participant->id)
            ->first();

        if ($log) {
            return $log;
        }

        return SurveyLog::create([
            'survey_id' => $surveyId,
            'participant_type' => $type,
            'participant_id' => $participant->id,
            'status' => true,
            'completed_at' => now(),
        ]);
    }

    public function hasParticipated(int $surveyId, Model $participant): bool
    {
        $survey = Survey::find($surveyId);
        if (! $survey) {
            return false;
        }

        $type = $survey->mapModelToTargetType($participant);

        return SurveyLog::where('survey_id', $surveyId)
            ->where('participant_type', $type)
            ->where('participant_id', $participant->id)
            ->exists();
    }

    public function getParticipationStats(int $surveyId): array
    {
        $logs = SurveyLog::where('survey_id', $surveyId)->get();

        return [
            'total' => $logs->count(),
            'by_type' => [
                'student' => $logs->where('participant_type', 'STUDENT')->count(),
                'teacher' => $logs->where('participant_type', 'TEACHER')->count(),
                'parent' => $logs->where('participant_type', 'PARENT')->count(),
            ],
        ];
    }
}
